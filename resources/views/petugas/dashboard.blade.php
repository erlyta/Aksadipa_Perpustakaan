@extends('layouts.app')

@section('title','Dashboard Petugas')

@push('styles')
<style>
    .staff-hero {
        background: linear-gradient(135deg, #0f172a, #1e3c72 52%, #264a8a);
        color: #f8fafc;
        border-radius: 20px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.22);
    }
    .staff-hero::after {
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
        top: -95px;
        right: -90px;
    }
    .staff-pill {
        background: rgba(255, 255, 255, 0.16);
        color: #e2e8f0;
        border-radius: 999px;
        padding: 6px 12px;
        font-weight: 700;
        font-size: 12px;
        letter-spacing: .2px;
    }
    .stat-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 16px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.16);
    }
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(30, 60, 114, 0.12);
        color: #1e3c72;
        font-size: 20px;
    }
    .stat-label {
        color: #5b677a;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .panel-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 16px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }
    .quick-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }
    .quick-link {
        border: 1px solid rgba(30, 60, 114, 0.18);
        background: #f8fafc;
        border-radius: 14px;
        padding: 14px;
        text-decoration: none;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        transition: all .2s ease;
    }
    .quick-link i {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(30, 60, 114, 0.12);
    }
    .quick-link:hover {
        color: #1e3c72;
        border-color: rgba(30, 60, 114, 0.34);
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
    }
    .loan-item {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 10px;
        background: #ffffff;
    }
    .loan-item:last-child {
        margin-bottom: 0;
    }
    .loan-user {
        font-weight: 700;
        color: #0f172a;
        margin-right: 6px;
    }
    .loan-book {
        color: #475569;
    }
    .loan-status {
        display: inline-block;
        margin-top: 6px;
        padding: 4px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: rgba(30, 60, 114, 0.12);
        color: #1e3c72;
        text-transform: capitalize;
    }
    @media (max-width: 767px) {
        .staff-hero {
            padding: 22px;
        }
        .quick-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="staff-hero mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center position-relative">
        <div>
            <div class="staff-pill mb-2">Dashboard Petugas</div>
            <h3 class="fw-bold mb-1">Pusat Operasional Harian</h3>
            <p class="mb-0 text-light opacity-75">Kelola buku, kategori, peminjaman, dan laporan dalam satu tempat.</p>
        </div>
        <div class="text-light opacity-75 mt-3 mt-md-0">
            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->full_name }}
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-book"></i></div>
                <div>
                    <div class="stat-label">Total Buku</div>
                    <h3 class="mb-0">{{ $totalBooks }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-tags"></i></div>
                <div>
                    <div class="stat-label">Total Kategori</div>
                    <h3 class="mb-0">{{ $totalCategories }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="stat-label">Menunggu Konfirmasi</div>
                    <h3 class="mb-0">{{ $pendingLoans }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-box-arrow-in-left"></i></div>
                <div>
                    <div class="stat-label">Menunggu Kembali</div>
                    <h3 class="mb-0">{{ $pendingReturns }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card panel-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Aksi Cepat Petugas</h5>
                    <small class="text-muted">Akses cepat untuk pekerjaan inti petugas.</small>
                </div>
                <span class="staff-pill">Shortcut</span>
            </div>
            <div class="quick-grid">
                <a href="{{ url('/books') }}" class="quick-link">
                    <i class="bi bi-book"></i>
                    <span>Kelola Buku</span>
                </a>
                <a href="{{ url('/categories') }}" class="quick-link">
                    <i class="bi bi-tags"></i>
                    <span>Kelola Kategori</span>
                </a>
                <a href="{{ url('/loans') }}" class="quick-link">
                    <i class="bi bi-check2-circle"></i>
                    <span>Konfirmasi Pinjam</span>
                </a>
                <a href="{{ route('reports.loans') }}" class="quick-link">
                    <i class="bi bi-printer"></i>
                    <span>Cetak Laporan</span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card panel-card p-4 h-100">
            <h5 class="fw-bold mb-3">Peminjaman Terbaru</h5>
            @if($latestLoans->count())
                @foreach($latestLoans as $loan)
                    <div class="loan-item">
                        <div>
                            <span class="loan-user">{{ optional($loan->user)->full_name ?? '-' }}</span>
                            <span class="loan-book">{{ optional($loan->book)->title ?? '-' }}</span>
                        </div>
                        <span class="loan-status">{{ str_replace('_', ' ', $loan->status ?? '-') }}</span>
                    </div>
                @endforeach
            @else
                <p class="text-muted mb-0">Belum ada data peminjaman.</p>
            @endif
        </div>
    </div>
</div>
@endsection
