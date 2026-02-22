<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\UserBookController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserProfileController;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect('/landing'));

Route::get('/landing', [LandingController::class, 'index'])->name('landing');

// auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PEMINJAM
|--------------------------------------------------------------------------
*/
Route::middleware(['checklogin', 'role:peminjam'])->group(function () {

    Route::get('/user/dashboard', function () {
        return redirect('/user/books');
    });

    Route::get('/user/books', [UserBookController::class, 'index'])->name('user.books.index');
    Route::get('/user/books/{book}', [UserBookController::class, 'show'])->name('user.books.show');
    Route::get('/user/favorites', [UserBookController::class, 'favorites'])->name('user.favorites.index');
    Route::post('/user/favorites/toggle', [UserBookController::class, 'toggleFavorite'])->name('user.favorites.toggle');
    Route::get('/user/loans', [UserBookController::class, 'history'])->name('user.loans.index');
    Route::get('/user/loans/create', [UserBookController::class, 'createLoan'])->name('user.loans.create');
    Route::get('/user/loans/{loan}', [UserBookController::class, 'showLoan'])->name('user.loans.show');
    Route::post('/user/loans', [UserBookController::class, 'storeLoan'])->name('user.loans.store');
    Route::delete('/user/loans/{loan}', [UserBookController::class, 'cancelLoan'])->name('user.loans.cancel');
    Route::post('/user/loans/{loan}/return', [UserBookController::class, 'requestReturn'])->name('user.loans.return');
    Route::get('/user/profile', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/user/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
});

/*
|--------------------------------------------------------------------------
| ADMIN & PETUGAS (FITUR BERSAMA)
|--------------------------------------------------------------------------
*/
Route::middleware(['checklogin', 'role:admin,petugas'])->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'petugas') {
        $now = \Carbon\Carbon::now();
        $pendingReturns = Loan::where('status', 'menunggu_kembali')
            ->orWhere(function ($query) use ($now) {
                $query->whereIn('status', ['dipinjam', 'menunggu'])
                    ->where(function ($overdue) use ($now) {
                        $overdue->whereNotNull('due_at')
                            ->where('due_at', '<', $now)
                            ->orWhere(function ($fallback) use ($now) {
                                $fallback->whereNull('due_at')
                                    ->whereNotNull('loan_date')
                                    ->whereNotNull('duration_days')
                                    ->whereRaw('DATE_ADD(loan_date, INTERVAL duration_days DAY) < ?', [$now->toDateString()]);
                            });
                    });
            })
            ->count();

        return view('petugas.dashboard', [
            'totalBooks' => Book::count(),
            'totalCategories' => Category::count(),
            'pendingLoans' => Loan::where('status', 'menunggu')->count(),
            'pendingReturns' => $pendingReturns,
            'latestLoans' => Loan::with(['user', 'book'])->latest()->take(5)->get(),
        ]);
    }

        return view('dashboard', [
            'totalBooks' => Book::count(),
            'totalCategories' => Category::count(),
            'totalLoans' => Loan::count(),
            'totalUsers' => User::count(),
            'latestBooks' => Book::latest()->take(5)->get(),
            'latestLoans' => Loan::latest()->take(5)->get(),
        ]);
    })->name('dashboard');

    // Buku & kategori: admin dan petugas boleh full CRUD
    Route::resource('books', BookController::class);
    Route::resource('categories', CategoryController::class);

    // Peminjaman: admin dan petugas boleh kelola penuh
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}/edit', [LoanController::class, 'edit'])->name('loans.edit');
    Route::put('/loans/{loan}', [LoanController::class, 'update'])->name('loans.update');
    Route::patch('/loans/{loan}', [LoanController::class, 'update']);
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');
    Route::get('/loans/code/{code}', [LoanController::class, 'editByCode'])->name('loans.editByCode');
    Route::post('/loans/{loan}/confirm', [LoanController::class, 'confirm'])->name('loans.confirm');
    Route::post('/loans/{loan}/confirm-return', [LoanController::class, 'confirmReturn'])->name('loans.confirmReturn');

    // Laporan: admin dan petugas
    Route::get('/reports/loans', [ReportController::class, 'loans'])->name('reports.loans');
    Route::get('/reports/loans/print', [ReportController::class, 'printLoans'])->name('reports.loans.print');
    Route::get('/reports/loans/excel', [ReportController::class, 'exportLoansExcel'])->name('reports.loans.excel');
});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['checklogin', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'users'])->name('admin.users.index');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroyUser'])->name('admin.users.destroy');

    Route::get('/admin/staff', [AdminUserController::class, 'staff'])->name('admin.staff.index');
    Route::post('/admin/staff', [AdminUserController::class, 'storeStaff'])->name('admin.staff.store');
    Route::put('/admin/staff/{user}', [AdminUserController::class, 'updateStaff'])->name('admin.staff.update');
    Route::delete('/admin/staff/{user}', [AdminUserController::class, 'destroyStaff'])->name('admin.staff.destroy');
});
