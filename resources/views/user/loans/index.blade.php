@extends('layouts.app')

@section('title','Riwayat Peminjaman')

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
    .hero-subtitle {
        color: #d6e0f5;
        font-size: 15px;
        margin: 0;
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
        margin-bottom: 8px;
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
    @media (max-width: 768px) {
        .catalog-hero { padding: 20px; flex-direction: column; align-items: flex-start; }
        .hero-stat { align-items: flex-start; }
        .catalog-hero .hero-title { font-size: 24px; }
    }
</style>
@endpush

@section('content')
<div class="books-container">
    <div class="catalog-hero">
        <div>
            <div class="hero-pill"><i class="bi bi-clock-history"></i> Riwayat peminjamanmu</div>
            <h2 class="hero-title">Riwayat Peminjaman</h2>
            <p class="hero-subtitle">Lihat semua aktivitas peminjamanmu.</p>
        </div>
        <div class="hero-stat">
            <strong>{{ $loans->count() }}</strong>
            <span>Transaksi</span>
        </div>
    </div>

    @if($loans->count())
        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($loans as $loan)
                    <tr>
                        @php
                            $durationUnit = $loan->duration_unit ?? 'day';

                            $dueAt = $loan->due_at
                                ? \Carbon\Carbon::parse($loan->due_at)
                                : null;
                            if (!$dueAt && $loan->loan_date && $loan->duration_days) {
                                $dueAt = \Carbon\Carbon::parse($loan->loan_date)->addDays($loan->duration_days);
                            }
                        @endphp
                        <td class="book-title">{{ $loan->loan_code ?? '-' }}</td>
                        <td>
                            @if($loan->book)
                                {{ $loan->book->title }}
                            @else
                                <span class="text-danger fw-semibold">Buku sudah dihapus</span>
                            @endif
                        </td>
                        <td>
                            @if($loan->loan_start_at)
                                {{ \Carbon\Carbon::parse($loan->loan_start_at)->format('Y-m-d H:i') }}
                            @else
                                {{ $loan->loan_date }}
                            @endif
                        </td>
                        <td>
                            @if($dueAt)
                                {{ $durationUnit === 'hour' ? $dueAt->format('Y-m-d H:i') : $dueAt->toDateString() }}
                            @else
                                {{ $loan->return_date ?? '-' }}
                            @endif
                        </td>
                        <td>
                            <span class="book-category">{{ $loan->status ? str_replace('_', ' ', $loan->status) : '-' }}</span>
                        </td>
                        <td>
                            {{ ($loan->display_fine_amount ?? 0) > 0 ? 'Rp ' . number_format($loan->display_fine_amount, 0, ',', '.') : '-' }}
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('user.loans.show', $loan->id) }}" class="btn-success-custom">
                                    <i class="bi bi-info-circle"></i> Detail
                                </a>
                                @if(($loan->status ?? '') === 'menunggu')
                                    <form action="{{ route('user.loans.cancel', $loan->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Batalkan peminjaman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger-custom">
                                            <i class="bi bi-x-circle"></i> Batal
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>Belum ada riwayat</h3>
            <p>Ajukan peminjaman dari katalog buku.</p>
            <a href="{{ route('user.books.index') }}" class="empty-link">
                <i class="bi bi-book"></i> Lihat Katalog
            </a>
        </div>
    @endif
</div>
@endsection
