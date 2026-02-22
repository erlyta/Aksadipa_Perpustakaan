@extends('layouts.app')

@section('title','Dashboard')

@push('styles')
<style>
    .dashboard-hero {
        background: linear-gradient(135deg, #0f172a, #1e3c72 55%, #264a8a);
        color: #f8fafc;
        border-radius: 20px;
        padding: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.24);
    }
    .dashboard-hero::after {
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: rgba(255, 183, 3, 0.24);
        top: -95px;
        right: -90px;
    }
    .stat-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 16px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
    }
    .stat-icon {
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
    .stat-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .quick-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 16px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }
    .soft-pill {
        background: rgba(30, 60, 114, 0.1);
        color: #1e3c72;
        border-radius: 999px;
        padding: 6px 12px;
        font-weight: 600;
        font-size: 12px;
    }
    .quick-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }
    .quick-link {
        border: 1px solid rgba(30, 60, 114, 0.2);
        background: #f8faff;
        border-radius: 14px;
        padding: 14px;
        text-decoration: none;
        color: #1e3c72;
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
        color: #17325e;
        border-color: rgba(30, 60, 114, 0.34);
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(30, 60, 114, 0.14);
    }
    .activity-item {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 10px;
        background: #ffffff;
    }
    .activity-item:last-child {
        margin-bottom: 0;
    }
    .activity-title {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .activity-meta {
        color: #64748b;
        font-size: 13px;
    }
    @media (max-width: 767px) {
        .dashboard-hero {
            padding: 22px;
        }
        .quick-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="dashboard-hero mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center position-relative">
        <div>
            <div class="soft-pill mb-2">Dashboard Admin</div>
            <h3 class="fw-bold mb-1">Pusat Kendali Perpustakaan</h3>
            <p class="mb-0 text-light opacity-75">Pantau statistik utama, akses fitur inti, dan evaluasi aktivitas terbaru.</p>
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
                <div class="stat-icon"><i class="bi bi-arrow-left-right"></i></div>
                <div>
                    <div class="stat-label">Total Peminjaman</div>
                    <h3 class="mb-0">{{ $totalLoans }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="bi bi-people"></i></div>
                <div>
                    <div class="stat-label">Total User</div>
                    <h3 class="mb-0">{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card quick-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Aktivitas Cepat</h5>
                    <small class="text-muted">Akses cepat untuk operasional inti admin.</small>
                </div>
                <span class="soft-pill">Shortcut</span>
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
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Data Peminjaman</span>
                </a>
                @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ url('/admin/users') }}" class="quick-link">
                    <i class="bi bi-people"></i>
                    <span>Akun User</span>
                </a>
                <a href="{{ url('/admin/staff') }}" class="quick-link">
                    <i class="bi bi-person-badge"></i>
                    <span>Akun Petugas</span>
                </a>
                <a href="{{ route('reports.loans') }}" class="quick-link">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Peminjaman</span>
                </a>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card quick-card p-4 h-100">
            <h5 class="fw-bold mb-3">Aktivitas Buku Terbaru</h5>
            @if($latestBooks->count())
                @foreach($latestBooks as $book)
                    <div class="activity-item">
                        <div class="activity-title">{{ $book->title }}</div>
                        <div class="activity-meta">
                            Kategori: {{ optional($book->category)->name ?? '-' }} |
                            Stok: {{ $book->stock ?? 0 }}
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted mb-0">Belum ada data buku terbaru.</p>
            @endif
        </div>
    </div>
</div>

@endsection
