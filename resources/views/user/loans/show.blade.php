@extends('layouts.app')

@section('title','Detail Peminjaman')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
<style>
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
    .status-dikembalikan { background: #dcfce7; color: #166534; }
    .status-terlambat { background: #fee2e2; color: #991b1b; }

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
    .review-card {
        margin-top: 16px;
        padding: 16px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 14px;
        background: #f8fafc;
    }
    .review-stars {
        color: #f59e0b;
        font-size: 14px;
        display: inline-flex;
        gap: 2px;
    }
    .review-comment {
        margin-top: 8px;
        color: #475467;
        font-size: 13px;
        white-space: pre-line;
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
        $durationText = $durationValue . ' hari';

        $loanStartAt = $loan->loan_start_at
            ? \Carbon\Carbon::parse($loan->loan_start_at)
            : ($loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->startOfDay() : null);

        $dueAt = $loan->due_at
            ? \Carbon\Carbon::parse($loan->due_at)
            : null;
        if (!$dueAt && $durationValue) {
            $startAtForDue = $loanStartAt ?: ($loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->startOfDay() : null);
            if ($startAtForDue) {
                $dueAt = $startAtForDue->copy()->addDays((int) $durationValue)->endOfDay();
            }
        }
        if (!$dueAt && $loan->loan_date && $loan->duration_days) {
            $dueAt = \Carbon\Carbon::parse($loan->loan_date)->addDays($loan->duration_days)->endOfDay();
        }

        $startText = $loanStartAt
            ? $loanStartAt->toDateString()
            : '-';
        $dueText = $dueAt
            ? $dueAt->toDateString()
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
            <a href="{{ route('user.loans.index') }}" class="detail-back-btn">
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

            @if(in_array(($loan->status ?? ''), ['dikembalikan', 'terlambat'], true))
                <div class="review-card">
                    <h5 class="mb-3">Rating dan Komentar</h5>

                    @if($loan->review)
                        <div class="mb-0">
                            <div class="review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= (int) $loan->review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            <div class="review-comment">
                                {{ $loan->review->comment ?: 'Tanpa komentar.' }}
                            </div>
                        </div>
                    @else
                        <form action="{{ route('user.loans.review', $loan->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <select id="rating" name="rating" class="form-control" required>
                                    <option value="">Pilih rating</option>
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ (int) old('rating') === $i ? 'selected' : '' }}>
                                            {{ $i }} bintang
                                        </option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Komentar</label>
                                <textarea id="comment" name="comment" class="form-control" rows="4" maxlength="1000" placeholder="Tulis pengalaman membaca buku ini...">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn-success-custom">
                                <i class="bi bi-send-check"></i> Simpan Ulasan
                            </button>
                        </form>
                    @endif
                </div>
            @endif
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
