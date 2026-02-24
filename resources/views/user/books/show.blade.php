@extends('layouts.app')

@section('title','Detail Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
<style>
    .detail-header {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
        padding: 20px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        background: linear-gradient(120deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.07);
    }
    .detail-back-btn {
        justify-self: end;
        align-self: center;
        width: auto;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 13px;
        border: 1px solid rgba(15, 23, 42, 0.1);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(6px);
        color: #1f2937;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        line-height: 1;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
        transition: transform .15s ease, box-shadow .15s ease, background-color .15s ease, border-color .15s ease, color .15s ease;
    }
    .detail-back-btn:hover {
        color: #0f172a;
        border-color: rgba(30, 60, 114, 0.25);
        background: #ffffff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
        transform: translateY(-1px);
        text-decoration: none;
    }
    .detail-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #1e3c72;
        background: rgba(30, 60, 114, 0.12);
        border-radius: 999px;
        padding: 6px 12px;
        margin-bottom: 10px;
    }
    .detail-wrap {
        display: grid;
        grid-template-columns: minmax(220px, 300px) 1fr;
        gap: 20px;
    }
    .detail-cover-panel,
    .detail-meta {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }
    .detail-cover-panel {
        padding: 14px;
    }
    .detail-cover {
        width: 100%;
        height: 410px;
        object-fit: contain;
        border-radius: 14px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
        background: #f1f5f9;
    }
    .detail-placeholder {
        width: 100%;
        height: 410px;
        border-radius: 14px;
        border: 1px dashed rgba(15, 23, 42, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        background: #f1f5f9;
    }
    .detail-meta {
        padding: 20px;
        display: flex;
        flex-direction: column;
    }
    .detail-meta h2 {
        margin: 0;
        font-size: clamp(1.7rem, 2.3vw, 2.1rem);
        line-height: 1.15;
        letter-spacing: -0.3px;
    }
    .detail-meta .author {
        color: #5b677a;
        margin-top: 8px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }
    .detail-meta .author i {
        width: 24px;
        height: 24px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(30, 60, 114, 0.12);
        color: #1e3c72;
        font-size: 12px;
    }
    .detail-meta .chips {
        margin: 16px 0 0;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }
    .detail-meta .book-chip {
        background: rgba(30, 60, 114, 0.1);
        color: #1e3c72;
        font-weight: 700;
    }
    .detail-meta .stock-badge {
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }
    .synopsis-box {
        margin-top: 18px;
        border: 1px solid rgba(15, 23, 42, 0.1);
        background: linear-gradient(145deg, #f8fafc, #f3f7fb);
        border-radius: 14px;
        padding: 16px;
        color: #334155;
        line-height: 1.65;
        position: relative;
    }
    .synopsis-box::before {
        content: "";
        position: absolute;
        left: 0;
        top: 12px;
        bottom: 12px;
        width: 4px;
        border-radius: 999px;
        background: linear-gradient(180deg, #1e3c72, #ffb703);
    }
    .detail-actions {
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px solid rgba(15, 23, 42, 0.08);
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .detail-actions .btn-success-custom,
    .detail-actions .btn-warning-custom {
        min-height: 40px;
    }
    .detail-actions form {
        display: inline-flex;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin: 16px 0 0;
    }
    .info-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 12px;
        padding: 13px;
        background: #f8fafc;
        font-size: 13px;
        color: #334155;
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
    }
    .info-card strong {
        display: block;
        font-size: 14px;
        color: #0f172a;
        margin-bottom: 4px;
    }
    .reviews-box {
        margin-top: 16px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 14px;
        background: #f8fafc;
        padding: 14px;
    }
    .reviews-summary {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: #f59e0b;
        font-size: 14px;
    }
    .review-item {
        padding: 10px 0;
        border-top: 1px solid rgba(15, 23, 42, 0.08);
    }
    .review-item:first-of-type {
        border-top: 0;
        padding-top: 0;
    }
    .review-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 4px;
    }
    .review-text {
        color: #334155;
        font-size: 13px;
        white-space: pre-line;
    }
    @media (max-width: 991.98px) {
        .detail-header {
            grid-template-columns: 1fr;
        }
        .detail-wrap { grid-template-columns: 1fr; }
        .detail-cover, .detail-placeholder { height: 340px; }
        .info-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 575.98px) {
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="books-container">
    <div class="detail-header">
        <div>
            <div class="detail-kicker"><i class="bi bi-journal-bookmark"></i> Detail Koleksi</div>
            <h2 class="hero-title mb-1"><i class="bi bi-book"></i> Detail Buku</h2>
            <div class="text-muted">Lihat informasi lengkap sebelum mengajukan peminjaman.</div>
        </div>
        <a href="{{ route('user.books.index') }}" class="detail-back-btn">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="detail-wrap">
        <div class="detail-cover-panel">
            @if($book->cover)
                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="detail-cover">
            @else
                <div class="detail-placeholder">Tanpa Cover</div>
            @endif
        </div>
        <div class="detail-meta">
            <h2>{{ $book->title }}</h2>
            <div class="author"><i class="bi bi-person"></i>{{ $book->author }}</div>
            <div class="chips">
                <span class="book-chip">{{ optional($book->category)->name ?? 'Tanpa Kategori' }}</span>
                <span class="stock-badge {{ $book->stock <= 5 ? 'low' : ($book->stock <= 15 ? 'medium' : 'high') }}">
                    Stok: {{ $book->stock }}
                </span>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <strong>Penerbit</strong>
                    {{ $book->publisher ?? '-' }}
                </div>
                <div class="info-card">
                    <strong>Tahun</strong>
                    {{ $book->year ?? '-' }}
                </div>
                <div class="info-card">
                    <strong>ISBN</strong>
                    {{ $book->isbn ?? '-' }}
                </div>
            </div>

            <div class="synopsis-box">
                {{ $book->synopsis ?? 'Deskripsi belum tersedia.' }}
            </div>
            <div class="reviews-box">
                @php
                    $avgRating = $book->reviews_avg_rating ? round((float) $book->reviews_avg_rating, 1) : null;
                    $reviewCount = (int) ($book->reviews_count ?? 0);
                    $filledStars = $avgRating ? (int) round($avgRating) : 0;
                @endphp
                <div class="reviews-summary">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= $filledStars ? 'bi-star-fill' : 'bi-star' }}"></i>
                    @endfor
                    <strong style="color:#0f172a;">
                        {{ $avgRating ? $avgRating . '/5' : 'Belum ada rating' }}
                    </strong>
                    <span style="color:#64748b;">({{ $reviewCount }} ulasan)</span>
                </div>

                @forelse($book->reviews->take(5) as $review)
                    <div class="review-item">
                        <div class="review-meta">
                            <strong>{{ optional($review->user)->full_name ?? 'Pengguna' }}</strong>
                            <span>-</span>
                            <span>{{ $review->rating }}/5</span>
                        </div>
                        <div class="review-text">{{ $review->comment ?: 'Tanpa komentar.' }}</div>
                    </div>
                @empty
                    <div class="review-text">Belum ada komentar untuk buku ini.</div>
                @endforelse
            </div>
            <div class="detail-actions">
                <a
                    href="{{ route('user.loans.create', ['book_id' => $book->id]) }}"
                    class="btn-success-custom {{ $book->stock <= 0 ? 'disabled' : '' }}"
                    data-stock="{{ $book->stock }}"
                    onclick="return handleLoanClick(event, this);"
                >
                    <i class="bi bi-qr-code-scan"></i> Ajukan Peminjaman
                </a>
                <form action="{{ route('user.favorites.toggle') }}" method="POST" style="display:inline">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button class="btn-warning-custom">
                        <i class="bi {{ ($isFavorite ?? false) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                        {{ ($isFavorite ?? false) ? 'Di Koleksi' : 'Tambah Koleksi' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function handleLoanClick(event, el) {
        const stock = Number(el.getAttribute('data-stock') || 0);
        if (stock <= 0) {
            event.preventDefault();
            alert('Stok buku habis. Silakan pilih buku lain.');
            return false;
        }
        return true;
    }
</script>
@endsection
