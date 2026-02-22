@extends('layouts.app')

@push('styles')
<style>
    .user-hero {
        background: linear-gradient(135deg, #0f172a, #1e3c72 55%, #264a8a);
        color: #f8fafc;
        border-radius: 18px;
        padding: 28px;
        position: relative;
        overflow: hidden;
    }
    .user-hero::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 183, 3, 0.2);
        top: -80px;
        right: -80px;
    }
    .user-stat {
        border: none;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .user-stat:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 50px rgba(15, 23, 42, 0.12);
    }
    .user-stat .icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(30, 60, 114, 0.1);
        color: #1e3c72;
        font-size: 20px;
    }
    .status-pill {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
        display: inline-block;
    }
    .status-menunggu { background: #3b82f6; }
    .status-dipinjam { background: #f59e0b; }
    .status-dikembalikan { background: #22c55e; }
    .status-terlambat { background: #ef4444; }
    .history-card {
        background: #fff;
        padding: 24px;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
    }
</style>
@endpush

@section('content')
<div class="user-hero mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center position-relative">
        <div>
            <div class="soft-pill mb-2">Dashboard Peminjam</div>
            <h3 class="hero-title fw-bold mb-1">Selamat datang, {{ auth()->user()->full_name }}</h3>
            <p class="mb-0 text-light opacity-75">Pantau riwayat dan status peminjamanmu di sini.</p>
        </div>
        <div class="text-light opacity-75 mt-3 mt-md-0">
            <i class="bi bi-book"></i> Total: {{ $loans->count() }} buku
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card user-stat p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon"><i class="bi bi-journal-bookmark"></i></div>
                <div>
                    <small class="text-muted">Total Buku Dipinjam</small>
                    <h3 class="mb-0">{{ $loans->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card user-stat p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <small class="text-muted">Status Aktif</small>
                    <h3 class="mb-0">{{ $loans->where('status','dipinjam')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card user-stat p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon"><i class="bi bi-check-circle"></i></div>
                <div>
                    <small class="text-muted">Sudah Dikembalikan</small>
                    <h3 class="mb-0">{{ $loans->where('status','dikembalikan')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="history-card">
    <h4 class="mb-3">Riwayat Peminjaman</h4>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                <tr>
                    <td class="fw-semibold">{{ $loan->book->title }}</td>
                    <td>{{ $loan->loan_date }}</td>
                    <td>{{ $loan->return_date ?? '-' }}</td>
                    <td>
                        <span class="status-pill status-{{ $loan->status ?? 'dipinjam' }}">
                            {{ $loan->status ?? 'dipinjam' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Belum ada peminjaman buku
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
