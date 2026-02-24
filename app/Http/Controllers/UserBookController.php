<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Favorite;
use App\Models\BookReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserBookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')
            ->orderBy('title')
            ->get();
        $favoriteBookIds = Favorite::where('user_id', Auth::id())->pluck('book_id')->toArray();
        return view('user.books.index', compact('books', 'favoriteBookIds'));
    }

    public function show(Book $book)
    {
        $book->load([
            'category',
            'reviews' => function ($query) {
                $query->with('user')->latest();
            },
        ]);
        $book->loadAvg('reviews', 'rating');
        $book->loadCount('reviews');

        $isFavorite = Favorite::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->exists();
        return view('user.books.show', compact('book', 'isFavorite'));
    }

    public function favorites()
    {
        $favorites = Favorite::with('book.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('user.favorites.index', compact('favorites'));
    }

    public function toggleFavorite(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('book_id', $validated['book_id'])
            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Buku dihapus dari koleksi.');
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'book_id' => $validated['book_id'],
        ]);

        return back()->with('success', 'Buku ditambahkan ke koleksi.');
    }

    public function history()
    {
        $loans = Loan::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('user.loans.index', compact('loans'));
    }

    public function showLoan(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        $loan->load('book.category');

        $review = null;
        if ($loan->book_id) {
            $review = BookReview::where('user_id', Auth::id())
                ->where('book_id', $loan->book_id)
                ->first();
        }
        $loan->setRelation('review', $review);

        return view('user.loans.show', compact('loan'));
    }

    public function cancelLoan(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        if ($loan->status !== 'menunggu') {
            return redirect()->route('user.loans.index')->with('success', 'Peminjaman sudah diproses dan tidak bisa dibatalkan.');
        }

        $book = Book::find($loan->book_id);
        if ($book) {
            $book->increment('stock');
        }

        $loan->delete();
        return redirect()->route('user.loans.index')->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    public function requestReturn(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        if ($loan->status !== 'dipinjam') {
            return redirect()->route('user.loans.index')->with('success', 'Pengembalian hanya untuk status dipinjam.');
        }

        $loan->status = 'menunggu_kembali';
        $loan->save();

        return redirect()->route('user.loans.index')->with('success', 'Permintaan pengembalian dikirim.');
    }

    public function submitReview(Request $request, Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($loan->status, ['dikembalikan', 'terlambat'], true)) {
            return redirect()->route('user.loans.show', $loan->id)
                ->with('success', 'Ulasan hanya bisa diberikan setelah pengembalian dikonfirmasi.');
        }

        if (!$loan->book_id) {
            return redirect()->route('user.loans.show', $loan->id)
                ->with('success', 'Buku tidak tersedia untuk diberi ulasan.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $existingReview = BookReview::where('user_id', Auth::id())
            ->where('book_id', $loan->book_id)
            ->first();

        $payload = [
            'rating' => $validated['rating'],
            'comment' => !empty(trim((string) ($validated['comment'] ?? '')))
                ? trim((string) $validated['comment'])
                : null,
        ];

        if ($existingReview) {
            $existingReview->update($payload);
        } else {
            BookReview::create([
                'loan_id' => $loan->id,
                'book_id' => $loan->book_id,
                'user_id' => Auth::id(),
                ...$payload,
            ]);
        }

        return redirect()->route('user.loans.show', $loan->id)
            ->with('success', 'Rating dan komentar berhasil disimpan.');
    }

    public function createLoan(Request $request)
    {
        $books = Book::with('category')->orderBy('title')->get();
        $selectedBookId = $request->query('book_id');
        $selectedBook = null;
        if ($selectedBookId) {
            $selectedBook = Book::find($selectedBookId);
        }
        return view('user.loans.create', compact('books', 'selectedBookId', 'selectedBook'));
    }

    public function storeLoan(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'duration_unit' => 'required|in:day',
            'duration_value' => 'required|integer|min:1|max:365',
        ]);

        if ((int) $validated['duration_value'] > 365) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'duration_value' => 'Durasi hari maksimal 365.',
            ]);
        }

        $loan = DB::transaction(function () use ($validated) {
            $book = Book::lockForUpdate()->findOrFail($validated['book_id']);
            if ($book->stock <= 0) {
                return null;
            }

            $durationUnit = $validated['duration_unit'];
            $durationValue = (int) $validated['duration_value'];

            $book->decrement('stock');

            return Loan::create([
                'loan_code' => $this->generateLoanCode(),
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'loan_date' => $validated['loan_date'],
                'loan_start_at' => null,
                'duration_days' => $durationUnit === 'day' ? $durationValue : null,
                'duration_unit' => $durationUnit,
                'duration_value' => $durationValue,
                'return_date' => null,
                'due_at' => null,
                'status' => 'menunggu',
            ]);
        });

        if (!$loan) {
            return redirect()->route('user.loans.create')->with('success', 'Stok buku habis.');
        }

        return redirect()->route('user.loans.create')->with('loan_code', $loan->loan_code);
    }

    private function generateLoanCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Loan::where('loan_code', $code)->exists());

        return $code;
    }
}
