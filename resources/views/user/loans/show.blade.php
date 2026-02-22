@extends('layouts.app')

@section('title','Detail Peminjaman')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
<style>
    .loan-detail-wrap {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
        gap: 20px;
    }
    .loan-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        padding: 20px;
    }
    .loan-hero {
        display: flex;
        gap: 16px;
        align-items: center;
        margin-bottom: 18px;
    }
    .loan-hero .cover {
        width: 110px;
        height: 150px;
        border-radius: 14px;
        object-fit: cover;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
        background: #f8fafc;
    }
    .loan-hero .cover.placeholder {
        display: grid;
        place-items: center;
        font-size: 12px;
        color: #94a3b8;
    }
    .loan-hero h3 {
        margin: 0 0 6px;
        font-size: 22px;
    }
    .loan-hero .meta {
        color: #667085;
        font-size: 13px;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }
    .status-dipinjam { background: #e0f2fe; color: #0369a1; }
    .status-menunggu { background: #fef3c7; color: #92400e; }
    .status-menunggu_kembali { background: #fde68a; color: #92400e; }
    .status-selesai { background: #dcfce7; color: #166534; }

    .loan-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }
    .info-tile {
        background: #f8fafc;
        border-radius: 14px;
        padding: 12px 14px;
        border: 1px solid rgba(15, 23, 42, 0.06);
    }
    .info-tile span {
        display: block;
        font-size: 12px;
        color: #667085;
        margin-bottom: 6px;
    }
    .info-tile strong {
        font-size: 14px;
        color: #101828;
        font-weight: 700;
    }
    .timeline {
        display: grid;
        gap: 12px;
    }
    .timeline-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .timeline-dot {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: rgba(33, 85, 205, 0.1);
        color: #1d4ed8;
    }
    .timeline-item h5 {
        margin: 0 0 4px;
        font-size: 14px;
    }
    .timeline-item p {
        margin: 0;
        font-size: 12px;
        color: #667085;
    }
    @media (max-width: 992px) {
        .loan-detail-wrap {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="books-container">
    @php
        $durationUnit = $loan->duration_unit ?? 'day';
        $durationValue = $loan->duration_value ?? $loan->duration_days ?? 0;
        $durationText = $durationValue . ' ' . ($durationUnit === 'hour' ? 'jam' : 'hari');

        $loanStartAt = $loan->loan_start_at
            ? \Carbon\Carbon::parse($loan->loan_start_at)
            : ($loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->startOfDay() : null);

        $dueAt = $loan->due_at
            ? \Carbon\Carbon::parse($loan->due_at)
            : null;
        if (!$dueAt && $durationValue) {
            $startAtForDue = $loanStartAt ?: ($loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->startOfDay() : null);
            if ($startAtForDue) {
                $dueAt = $durationUnit === 'hour'
                    ? $startAtForDue->copy()->addHours((int) $durationValue)
                    : $startAtForDue->copy()->addDays((int) $durationValue);
            }
        }
        if (!$dueAt && $loan->loan_date && $loan->duration_days) {
            $dueAt = \Carbon\Carbon::parse($loan->loan_date)->addDays($loan->duration_days);
        }

        $startText = $loanStartAt
            ? ($durationUnit === 'hour' ? $loanStartAt->format('Y-m-d H:i') : $loanStartAt->toDateString())
            : '-';
        $dueText = $dueAt
            ? ($durationUnit === 'hour' ? $dueAt->format('Y-m-d H:i') : $dueAt->toDateString())
            : ($loan->return_date ?? '-');

        $statusRaw = $loan->status ?? '-';
        $statusKey = $loan->status ? strtolower($loan->status) : 'dipinjam';
        $statusLabel = $loan->status ? str_replace('_', ' ', $loan->status) : '-';
    @endphp

    <div class="page-header">
        <div>
            <h2 class="hero-title"><i class="bi bi-info-circle"></i> Detail Peminjaman</h2>
            <div class="text-muted mt-1">Informasi lengkap peminjamanmu.</div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('user.loans.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="loan-detail-wrap">
        <div class="loan-card">
            <div class="loan-hero">
                @if(optional($loan->book)->cover)
                    <img src="{{ asset('storage/' . $loan->book->cover) }}" alt="Cover {{ optional($loan->book)->title }}" class="cover">
                @else
                    <div class="cover placeholder">Tanpa Cover</div>
                @endif
                <div>
                    <h3>{{ optional($loan->book)->title ?? 'Buku sudah dihapus' }}</h3>
                    <div class="meta">
                        @if($loan->book)
                            {{ optional($loan->book)->author ?? '-' }} · {{ optional(optional($loan->book)->category)->name ?? 'Tanpa Kategori' }}
                        @else
                            Data buku tidak tersedia karena sudah dihapus.
                        @endif
                    </div>
                    <div class="meta mt-2">Kode Peminjaman: <strong>{{ $loan->loan_code ?? '-' }}</strong></div>
                </div>
            </div>

            <div class="loan-info-grid">
                <div class="info-tile">
                    <span>Tanggal Pinjam</span>
                    <strong>{{ $startText }}</strong>
                </div>
                <div class="info-tile">
                    <span>Durasi</span>
                    <strong>{{ $durationText }}</strong>
                </div>
                <div class="info-tile">
                    <span>Estimasi Kembali</span>
                    <strong>{{ $dueText }}</strong>
                </div>
                <div class="info-tile">
                    <span>Status</span>
                    <strong>
                        <span class="status-badge status-{{ $statusKey }}">
                            <i class="bi bi-lightning-charge"></i>
                            {{ $statusLabel }}
                        </span>
                    </strong>
                </div>
                <div class="info-tile">
                    <span>Keterlambatan</span>
                    <strong>{{ $loan->display_late_days ?? 0 }} hari</strong>
                </div>
                <div class="info-tile">
                    <span>Denda</span>
                    <strong>
                        {{ ($loan->display_fine_amount ?? 0) > 0 ? 'Rp ' . number_format($loan->display_fine_amount, 0, ',', '.') : '-' }}
                    </strong>
                </div>
            </div>

            <div class="form-actions" style="margin-top:16px;">
                @if(($loan->status ?? '') === 'dipinjam')
                    <form action="{{ route('user.loans.return', $loan->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Ajukan pengembalian buku ini?')">
                        @csrf
                        <button class="btn-warning-custom">
                            <i class="bi bi-box-arrow-in-left"></i> Ajukan Pengembalian
                        </button>
                    </form>
                @elseif(($loan->status ?? '') === 'menunggu_kembali')
                    <span class="text-muted">Menunggu konfirmasi pengembalian dari petugas.</span>
                @endif
            </div>
        </div>

        <div class="loan-card">
            <h4 class="mb-3">Timeline Peminjaman</h4>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"><i class="bi bi-calendar-event"></i></div>
                    <div>
                        <h5>Mulai Dipinjam</h5>
                        <p>{{ $startText }}</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <h5>Durasi</h5>
                        <p>{{ $durationText }}</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"><i class="bi bi-calendar-check"></i></div>
                    <div>
                        <h5>Estimasi Kembali</h5>
                        <p>{{ $dueText }}</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"><i class="bi bi-flag"></i></div>
                    <div>
                        <h5>Status Saat Ini</h5>
                        <p>{{ $statusLabel }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
