<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with(['user', 'book'])->latest()->get();
        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->ensureStaffAccess();

        $users = User::where('role', 'peminjam')->orderBy('full_name')->get();
        $books = Book::orderBy('title')->get();
        return view('loans.create', compact('users', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->ensureStaffAccess();

        $validated = $request->validate([
            'loan_code' => 'nullable|string|max:20|unique:loans,loan_code',
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'loan_start_at' => 'nullable|date',
            'duration_days' => 'nullable|integer|min:1|max:365',
            'duration_unit' => 'nullable|in:day,hour',
            'duration_value' => 'nullable|integer|min:1|max:365',
            'return_date' => 'nullable|date|after_or_equal:loan_date',
            'due_at' => 'nullable|date',
            'status' => 'nullable|in:dipinjam,dikembalikan,terlambat,menunggu,menunggu_kembali',
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = 'dipinjam';
        }

        if (empty($validated['loan_code'])) {
            $validated['loan_code'] = $this->generateLoanCode();
        }

        $validated = $this->enrichLoanTimingData($validated);

        Loan::create($validated);
        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->ensureStaffAccess();

        $loan = Loan::findOrFail($id);
        $users = User::where('role', 'peminjam')->orderBy('full_name')->get();
        $books = Book::orderBy('title')->get();
        return view('loans.edit', compact('loan', 'users', 'books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->ensureStaffAccess();

        $loan = Loan::findOrFail($id);

        $validated = $request->validate([
            'loan_code' => 'nullable|string|max:20|unique:loans,loan_code,' . $loan->id,
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'loan_start_at' => 'nullable|date',
            'duration_days' => 'nullable|integer|min:1|max:365',
            'duration_unit' => 'nullable|in:day,hour',
            'duration_value' => 'nullable|integer|min:1|max:365',
            'return_date' => 'nullable|date|after_or_equal:loan_date',
            'due_at' => 'nullable|date',
            'status' => 'required|in:dipinjam,dikembalikan,terlambat,menunggu,menunggu_kembali',
        ]);

        $validated = $this->enrichLoanTimingData($validated);

        $loan->update($validated);
        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->ensureStaffAccess();

        $loan = Loan::findOrFail($id);
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function confirm(Loan $loan)
    {
        if ($loan->status !== 'menunggu') {
            return redirect()->route('loans.index')->with('success', 'Peminjaman sudah diproses.');
        }

        $confirmedAt = Carbon::now();
        $durationUnit = $loan->duration_unit ?? 'day';
        $durationValue = $loan->duration_value ?? $loan->duration_days ?? 1;
        $durationValue = max(1, (int) $durationValue);

        $dueAt = $durationUnit === 'hour'
            ? $confirmedAt->copy()->addHours($durationValue)
            : $confirmedAt->copy()->addDays($durationValue);

        $loan->status = 'dipinjam';
        $loan->loan_date = $confirmedAt->toDateString();
        $loan->loan_start_at = $confirmedAt;
        $loan->duration_unit = $durationUnit;
        $loan->duration_value = $durationValue;
        $loan->duration_days = $durationUnit === 'day' ? $durationValue : null;
        $loan->due_at = $dueAt;
        $loan->return_date = $dueAt->toDateString();
        $loan->save();

        return redirect()->route('loans.index')->with('success', 'Peminjaman dikonfirmasi.');
    }

    public function confirmReturn(Loan $loan)
    {
        if ($loan->status !== 'menunggu_kembali') {
            return redirect()->route('loans.index')->with('success', 'Pengembalian sudah diproses.');
        }

        $book = Book::find($loan->book_id);
        if ($book) {
            $book->increment('stock');
        }

        $returnedDate = Carbon::now();
        $dueDate = $this->resolveDueDate($loan);
        $lateDays = 0;

        if ($dueDate && $returnedDate->greaterThan($dueDate)) {
            $durationUnit = $loan->duration_unit ?? 'day';
            if ($durationUnit === 'hour') {
                $lateHours = max(1, $dueDate->diffInHours($returnedDate));
                $lateDays = (int) ceil($lateHours / 24);
            } else {
                $lateDays = max(1, (int) floor($dueDate->diffInDays($returnedDate)));
            }
        }

        $finePerDay = 1000;
        $fineAmount = $lateDays * $finePerDay;

        $loan->status = $lateDays > 0 ? 'terlambat' : 'dikembalikan';
        $loan->late_days = $lateDays;
        $loan->fine_amount = $fineAmount;
        $loan->return_date = $returnedDate->toDateString();
        $loan->save();

        $message = $fineAmount > 0
            ? 'Pengembalian dikonfirmasi. Denda: Rp ' . number_format($fineAmount, 0, ',', '.') . '.'
            : 'Pengembalian dikonfirmasi.';

        return redirect()->route('loans.index')->with('success', $message);
    }

    public function editByCode(string $code)
    {
        $this->ensureStaffAccess();

        $loan = Loan::where('loan_code', $code)->first();
        if (!$loan) {
            return redirect()->route('loans.index')->with('success', 'Kode tidak ditemukan.');
        }
        return redirect()->route('loans.edit', $loan->id);
    }

    private function generateLoanCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Loan::where('loan_code', $code)->exists());

        return $code;
    }

    private function ensureStaffAccess(): void
    {
        abort_unless(
            Auth::check() && in_array(Auth::user()->role, ['admin', 'petugas'], true),
            403
        );
    }

    private function resolveDueDate(Loan $loan): ?Carbon
    {
        if ($loan->due_at) {
            return Carbon::parse($loan->due_at);
        }

        if ($loan->loan_date && $loan->duration_days) {
            return Carbon::parse($loan->loan_date)->addDays((int) $loan->duration_days)->startOfDay();
        }

        if ($loan->return_date) {
            return Carbon::parse($loan->return_date)->startOfDay();
        }

        return null;
    }

    private function enrichLoanTimingData(array $validated): array
    {
        $loanStartAt = !empty($validated['loan_start_at'])
            ? Carbon::parse($validated['loan_start_at'])
            : Carbon::parse($validated['loan_date'])->startOfDay();

        $durationUnit = $validated['duration_unit'] ?? 'day';

        $durationValue = $validated['duration_value'] ?? null;
        if ($durationValue === null && !empty($validated['duration_days'])) {
            $durationValue = (int) $validated['duration_days'];
        }

        if ($durationValue === null && !empty($validated['return_date'])) {
            $durationValue = max(
                1,
                Carbon::parse($validated['loan_date'])->diffInDays(Carbon::parse($validated['return_date']))
            );
        }

        $durationValue = (int) ($durationValue ?? 1);

        $dueAt = !empty($validated['due_at'])
            ? Carbon::parse($validated['due_at'])
            : $loanStartAt->copy()->addDays($durationValue);

        if (empty($validated['due_at']) && $durationUnit === 'hour') {
            $dueAt = $loanStartAt->copy()->addHours($durationValue);
        }

        $validated['loan_start_at'] = $loanStartAt;
        $validated['duration_unit'] = $durationUnit;
        $validated['duration_value'] = $durationValue;
        $validated['duration_days'] = $durationUnit === 'day' ? $durationValue : null;
        $validated['due_at'] = $dueAt;

        if (empty($validated['return_date'])) {
            $validated['return_date'] = $dueAt->toDateString();
        }

        return $validated;
    }
}
