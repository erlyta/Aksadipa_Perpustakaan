@extends('layouts.app')

@section('title','Katalog Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
<style>
    .books-container {
        position: relative;
        padding: 22px 20px 36px;
        background:
            radial-gradient(900px 500px at 120% -20%, rgba(30, 60, 114, 0.18), transparent 60%),
            radial-gradient(700px 420px at -10% -10%, rgba(30, 60, 114, 0.12), transparent 65%),
            #e9eff8;
        border-radius: 20px;
    }
    .catalog-hero {
        display: flex;
        gap: 24px;
        align-items: center;
        justify-content: space-between;
        padding: 24px 26px;
        border-radius: 18px;
        background: linear-gradient(120deg, #0f172a 0%, #1e3c72 65%, #274c8c 100%);
        border: 1px solid rgba(15, 23, 42, 0.2);
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.18);
        margin-bottom: 18px;
        position: relative;
        overflow: hidden;
    }
    .catalog-hero::after {
        content: "";
        position: absolute;
        right: -80px;
        top: -60px;
        width: 240px;
        height: 240px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.25), transparent 70%);
        filter: blur(1px);
    }
    .hero-title {
        font-family: "Playfair Display", serif;
        font-size: 28px;
        font-weight: 700;
        color: #ffffff !important;
        text-shadow: 0 2px 10px rgba(15, 23, 42, 0.35);
        margin: 0 0 8px;
        letter-spacing: -0.3px;
        line-height: 1.15;
    }
    .hero-subtitle {
        color: #d6e0f5;
        font-size: 15px;
        margin: 0 0 14px;
        max-width: 520px;
        font-family: "Space Grotesk", sans-serif;
        line-height: 1.6;
    }
    .hero-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16);
        color: #e2e8f0;
        font-size: 12px;
        font-weight: 600;
        font-family: "Space Grotesk", sans-serif;
    }
    .hero-stat {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        font-family: "Space Grotesk", sans-serif;
    }
    .hero-stat strong {
        font-size: 24px;
        color: #ffffff;
        text-shadow: 0 2px 10px rgba(15, 23, 42, 0.35);
    }
    .hero-stat span {
        color: #d6e0f5;
        font-size: 12px;
    }
    .catalog-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }
    .catalog-stat-card {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 14px;
        padding: 12px 14px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .catalog-stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: rgba(30, 60, 114, 0.12);
        color: #1e3c72;
        font-size: 18px;
    }
    .catalog-stat-label {
        font-size: 12px;
        color: #5b677a;
        margin: 0;
    }
    .catalog-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin: 2px 0 0;
    }
    .catalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, max-content));
        gap: 6px;
        justify-content: start;
    }
    .book-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform .2s ease, box-shadow .2s ease;
        animation: rise .5s ease both;
        position: relative;
        max-width: 220px;
        margin: 0;
    }
    .book-card-inner {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
    }
    .book-cover {
        width: 100%;
        aspect-ratio: 3 / 4;
        height: auto;
        object-fit: contain;
        background: #f3f4f6;
        display: block;
    }
    .book-cover.placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 12px;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        aspect-ratio: 3 / 4;
        height: auto;
        background: #f3f4f6;
    }
    .book-meta {
        padding: 12px 14px 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
        font-family: "Space Grotesk", sans-serif;
    }
    .book-title {
        font-family: "Playfair Display", serif;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        font-size: 15px;
        line-height: 1.35;
        letter-spacing: -0.2px;
        min-height: 40px;
    }
    .book-title a {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .book-author {
        color: #5b677a;
        font-size: 13px;
        line-height: 1.5;
        min-height: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .book-chip {
        display: inline-block;
        background: rgba(30, 60, 114, 0.12);
        color: #1e3c72;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 6px;
        font-family: "Space Grotesk", sans-serif;
    }
    .book-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 2px;
    }
    .stock-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: rgba(16, 185, 129, 0.12);
        color: #047857;
    }
    .stock-pill.low {
        background: rgba(239, 68, 68, 0.14);
        color: #b91c1c;
    }
    .book-actions {
        padding: 0 14px 14px;
        margin-top: auto;
        font-family: "Space Grotesk", sans-serif;
    }
    .book-actions .btn-success-custom,
    .book-actions .btn-warning-custom {
        flex: 1;
        justify-content: center;
    }
    .book-actions .btn-warning-custom {
        background: #1e3c72;
    }
    .book-actions .btn-warning-custom:hover {
        background: #17325e;
    }
    .book-card:nth-child(1) { animation-delay: 0.02s; }
    .book-card:nth-child(2) { animation-delay: 0.05s; }
    .book-card:nth-child(3) { animation-delay: 0.08s; }
    .book-card:nth-child(4) { animation-delay: 0.11s; }
    .book-card:nth-child(5) { animation-delay: 0.14s; }
    .book-card:nth-child(6) { animation-delay: 0.17s; }
    .book-card:nth-child(7) { animation-delay: 0.20s; }
    .book-card:nth-child(8) { animation-delay: 0.23s; }
    @keyframes rise {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @media (max-width: 1200px) {
        .catalog-grid { grid-template-columns: repeat(auto-fit, minmax(200px, max-content)); }
        .catalog-hero { flex-direction: column; align-items: flex-start; }
        .hero-stat { align-items: flex-start; }
    }
    @media (max-width: 768px) {
        .catalog-grid { grid-template-columns: repeat(auto-fit, minmax(180px, max-content)); }
        .catalog-hero { padding: 20px; }
        .hero-title { font-size: 24px; }
        .catalog-stats { grid-template-columns: 1fr; }
    }
    @media (max-width: 480px) {
        .catalog-grid { grid-template-columns: 1fr; }
        .book-cover,
        .book-cover.placeholder {
            height: 300px;
        }
    }
</style>
@endpush

@section('content')
<div class="books-container">
    <div class="catalog-hero">
        <div>
            <div class="hero-pill"><i class="bi bi-compass"></i> Jelajahi koleksi terbaru</div>
            <h2 class="hero-title">Katalog Buku Pilihan</h2>
            <p class="hero-subtitle">
                Temukan bacaan favoritmu, simpan ke koleksi, dan ajukan peminjaman hanya dalam beberapa klik.
            </p>
        </div>
        <div class="hero-stat">
            <strong>{{ $books->count() }}</strong>
            <span>Judul tersedia</span>
        </div>
    </div>
    <div class="catalog-stats">
        <div class="catalog-stat-card">
            <div class="catalog-stat-icon"><i class="bi bi-book"></i></div>
            <div>
                <p class="catalog-stat-label">Total Buku</p>
                <p class="catalog-stat-value">{{ $books->count() }}</p>
            </div>
        </div>
        <div class="catalog-stat-card">
            <div class="catalog-stat-icon"><i class="bi bi-heart"></i></div>
            <div>
                <p class="catalog-stat-label">Koleksi Favorit</p>
                <p class="catalog-stat-value">{{ count($favoriteBookIds ?? []) }}</p>
            </div>
        </div>
    </div>

    @if(session('loan_code'))
        <div class="alert-success-custom">
            <i class="bi bi-check-circle"></i>
            Permintaan pinjam berhasil. Kode kamu:
            <strong>{{ session('loan_code') }}</strong>
            (Tunjukkan ke petugas untuk diproses)
        </div>
    @endif

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-info-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($books->count())
        <div class="catalog-grid">
            @foreach($books as $book)
                <div class="book-card">
                    <div class="book-card-inner">
                    <a href="{{ route('user.books.show', $book->id) }}" style="text-decoration:none;color:inherit">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="book-cover">
                        @else
                            <div class="book-cover placeholder">Tanpa Cover</div>
                        @endif
                    </a>
                    <div class="book-meta">
                        <h5 class="book-title">
                            <a href="{{ route('user.books.show', $book->id) }}" style="text-decoration:none;color:inherit">
                                {{ $book->title }}
                            </a>
                        </h5>
                        <div class="book-author">{{ $book->author }}</div>
                        <div class="book-badges">
                            <span class="book-chip">{{ optional($book->category)->name ?? 'Tanpa Kategori' }}</span>
                            <span class="stock-pill {{ $book->stock <= 5 ? 'low' : '' }}">
                                <i class="bi bi-box-seam"></i>
                                {{ $book->stock > 0 ? $book->stock . ' tersedia' : 'Habis' }}
                            </span>
                        </div>
                    </div>
                    <div class="book-actions">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('user.books.show', $book->id) }}" class="btn-success-custom">
                                <i class="bi bi-info-circle"></i> Detail
                            </a>
                            <form action="{{ route('user.favorites.toggle') }}" method="POST" style="display:inline">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <button class="btn-warning-custom">
                                    <i class="bi {{ in_array($book->id, $favoriteBookIds ?? []) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                    {{ in_array($book->id, $favoriteBookIds ?? []) ? 'Favorit' : 'Simpan' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>Belum ada buku</h3>
            <p>Koleksi buku akan muncul di sini.</p>
        </div>
    @endif
</div>
@endsection
