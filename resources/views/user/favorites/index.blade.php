@extends('layouts.app')

@section('title','Koleksi Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
<style>
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
    .catalog-hero .hero-title {
        font-family: "Playfair Display", serif;
        font-size: 28px;
        font-weight: 700;
        color: #ffffff;
        text-shadow: 0 2px 10px rgba(15, 23, 42, 0.35);
        margin: 0 0 8px;
        letter-spacing: -0.3px;
        line-height: 1.15;
    }
    .catalog-hero .hero-title::after {
        content: "";
        display: block;
        width: min(160px, 55%);
        height: 6px;
        border-radius: 999px;
        margin-top: 8px;
        background: linear-gradient(90deg, #ffb703, rgba(255, 183, 3, 0.2));
    }
    .hero-subtitle {
        color: #d6e0f5;
        font-size: 15px;
        margin: 0 0 14px;
        max-width: 520px;
        font-family: "Space Grotesk", sans-serif;
        line-height: 1.6;
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
        color: #f8fafc;
    }
    .hero-stat span {
        color: #d6e0f5;
        font-size: 12px;
    }
    .favorite-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, max-content));
        gap: 6px;
        justify-content: start;
    }
    .favorite-card {
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
    .favorite-card-inner {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .favorite-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
    }
    .favorite-cover {
        width: 100%;
        aspect-ratio: 3 / 4;
        height: auto;
        object-fit: contain;
        background: #f3f4f6;
        display: block;
    }
    .favorite-cover.placeholder {
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
    .favorite-body {
        padding: 12px 14px 6px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
        font-family: "Space Grotesk", sans-serif;
    }
    .favorite-title {
        font-family: "Playfair Display", serif;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        font-size: 15px;
        line-height: 1.35;
        letter-spacing: -0.2px;
    }
    .favorite-author {
        color: #5b677a;
        font-size: 13px;
        line-height: 1.5;
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
    .favorite-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    .favorite-actions {
        padding: 8px 14px 14px;
        font-family: "Space Grotesk", sans-serif;
    }
    .favorite-actions .btn-success-custom,
    .favorite-actions .btn-danger-custom {
        flex: 1;
        justify-content: center;
    }
    .favorite-card:nth-child(1) { animation-delay: 0.02s; }
    .favorite-card:nth-child(2) { animation-delay: 0.05s; }
    .favorite-card:nth-child(3) { animation-delay: 0.08s; }
    .favorite-card:nth-child(4) { animation-delay: 0.11s; }
    .favorite-card:nth-child(5) { animation-delay: 0.14s; }
    .favorite-card:nth-child(6) { animation-delay: 0.17s; }
    .favorite-card:nth-child(7) { animation-delay: 0.20s; }
    .favorite-card:nth-child(8) { animation-delay: 0.23s; }
    @keyframes rise {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .empty-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(30, 60, 114, 0.12);
        color: #1e3c72;
        text-decoration: none;
        font-weight: 600;
    }
    .empty-link:hover {
        background: rgba(30, 60, 114, 0.2);
        color: #17325e;
        text-decoration: none;
    }
    @media (max-width: 1200px) {
        .favorite-grid { grid-template-columns: repeat(auto-fit, minmax(200px, max-content)); }
        .catalog-hero { flex-direction: column; align-items: flex-start; }
        .hero-stat { align-items: flex-start; }
    }
    @media (max-width: 768px) {
        .favorite-grid { grid-template-columns: repeat(auto-fit, minmax(180px, max-content)); }
        .catalog-hero { padding: 20px; }
        .catalog-hero .hero-title { font-size: 24px; }
    }
    @media (max-width: 480px) {
        .favorite-grid { grid-template-columns: 1fr; }
        .favorite-cover,
        .favorite-cover.placeholder {
            height: 320px;
        }
    }
</style>
@endpush

@section('content')
<div class="books-container">
    <div class="catalog-hero">
        <div>
            <div class="hero-pill"><i class="bi bi-heart"></i> Favorit pilihanmu</div>
            <h2 class="hero-title">Koleksi Saya</h2>
            <p class="hero-subtitle">Buku-buku favorit yang kamu simpan.</p>
        </div>
        <div class="hero-stat">
            <strong>{{ $favorites->count() }}</strong>
            <span>Buku tersimpan</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-info-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($favorites->count())
        <div class="favorite-grid">
            @foreach($favorites as $fav)
                <div class="favorite-card">
                    <div class="favorite-card-inner">
                        <a href="{{ route('user.books.show', $fav->book->id) }}" style="text-decoration:none;color:inherit">
                            @if($fav->book->cover)
                                <img src="{{ asset('storage/' . $fav->book->cover) }}" alt="Cover {{ $fav->book->title }}" class="favorite-cover">
                            @else
                                <div class="favorite-cover placeholder">Tanpa Cover</div>
                            @endif
                        </a>
                        <div class="favorite-body">
                            <h5 class="favorite-title">
                                <a href="{{ route('user.books.show', $fav->book->id) }}" style="text-decoration:none;color:inherit">
                                    {{ $fav->book->title }}
                                </a>
                            </h5>
                            <div class="favorite-author">{{ $fav->book->author }}</div>
                            <div class="favorite-meta">
                                <span class="book-chip">{{ optional($fav->book->category)->name ?? 'Tanpa Kategori' }}</span>
                                <span class="stock-pill {{ $fav->book->stock <= 5 ? 'low' : '' }}">
                                    <i class="bi bi-box-seam"></i>
                                    {{ $fav->book->stock > 0 ? $fav->book->stock . ' tersedia' : 'Habis' }}
                                </span>
                            </div>
                        </div>
                        <div class="favorite-actions">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('user.books.show', $fav->book->id) }}" class="btn-success-custom">
                                    <i class="bi bi-info-circle"></i> Detail
                                </a>
                                <form action="{{ route('user.favorites.toggle') }}" method="POST" style="display:inline">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $fav->book->id }}">
                                    <button class="btn-danger-custom">
                                        <i class="bi bi-heartbreak"></i> Hapus
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
            <h3>Koleksi masih kosong</h3>
            <p>Tambahkan buku ke koleksi favoritmu dari katalog.</p>
            <a href="{{ route('user.books.index') }}" class="empty-link">
                <i class="bi bi-book"></i> Lihat Katalog
            </a>
        </div>
    @endif
</div>
@endsection
