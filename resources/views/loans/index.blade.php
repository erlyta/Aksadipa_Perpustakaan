@extends('layouts.app')

@section('title','Peminjaman')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">
    <div class="page-header">
        <div>
            <h2><i class="bi bi-arrow-left-right"></i> Peminjaman</h2>
            <div class="text-muted mt-1">Kelola data peminjaman buku.</div>
        </div>
        <div class="page-header-actions">
            <button class="btn-primary-custom" type="button" data-bs-toggle="modal" data-bs-target="#scanModal">
                <i class="bi bi-qr-code-scan"></i> Scan Kode
            </button>
            <a href="{{ route('loans.create') }}" class="btn-primary-custom">
                <i class="bi bi-plus-circle"></i> Tambah Peminjaman
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($loans->count())
        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama User</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($loans as $loan)
                    <tr>
                        <td class="book-title">{{ $loan->loan_code ?? '-' }}</td>
                        <td>{{ optional($loan->user)->full_name ?? '-' }}</td>
                        <td>{{ optional($loan->book)->title ?? '-' }}</td>
                        <td>
                            @if($loan->loan_start_at)
                                {{ \Carbon\Carbon::parse($loan->loan_start_at)->format('Y-m-d H:i') }}
                            @else
                                {{ $loan->loan_date }}
                            @endif
                        </td>
                        <td>
                            @php
                                $durationUnit = $loan->duration_unit ?? 'day';
                                $durationValue = $loan->duration_value ?? $loan->duration_days;
                            @endphp
                            {{ ($durationValue ?? '-') . ' ' . ($durationUnit === 'hour' ? 'jam' : 'hari') }}
                        </td>
                        <td>
                            @php
                                $status = $loan->status ?? 'dipinjam';
                                $isOverdue = ($loan->display_late_days ?? 0) > 0
                                    && !in_array($status, ['dikembalikan', 'terlambat'], true);
                            @endphp
                            <span class="book-category {{ $isOverdue ? 'overdue' : '' }}">
                                {{ $isOverdue ? 'Terlambat' : str_replace('_', ' ', ucfirst($status)) }}
                            </span>
                        </td>
                        <td>
                            {{ ($loan->display_fine_amount ?? 0) > 0 ? 'Rp ' . number_format($loan->display_fine_amount, 0, ',', '.') : '-' }}
                        </td>
                        <td>
                            <div class="action-buttons">
                                @if($status === 'menunggu')
                                <form action="{{ route('loans.confirm', $loan->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button class="btn-success-custom">
                                        <i class="bi bi-check2-circle"></i> Konfirmasi
                                    </button>
                                </form>
                                @endif
                                @if($status === 'menunggu_kembali')
                                <form action="{{ route('loans.confirmReturn', $loan->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button class="btn-success-custom">
                                        <i class="bi bi-box-arrow-in-left"></i> Konfirmasi Kembali
                                    </button>
                                </form>
                                @endif

                                <a href="{{ route('loans.edit', $loan->id) }}" class="btn-warning-custom">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('loans.destroy', $loan->id) }}"
                                      method="POST"
                                      data-confirm-title="Hapus Peminjaman"
                                      data-confirm-message="Yakin ingin menghapus data ini?"
                                      data-confirm-button-text="Ya, Hapus"
                                      style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger-custom">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
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
            <h3>Belum ada peminjaman</h3>
            <p>Mulai catat peminjaman buku di sini.</p>
        </div>
    @endif
</div>

<!-- Scan Modal (placeholder UI) -->
<div class="modal fade" id="scanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Kode Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Arahkan kamera ke kode yang ditunjukkan user atau ketik manual.</p>
                <form id="scanForm">
                    <div class="form-group mb-0">
                        <label for="loan_code">Kode Peminjaman</label>
                        <input type="text" id="loan_code" class="form-control" placeholder="Contoh: ID peminjaman">
                        <div class="invalid-feedback">Kode tidak boleh kosong.</div>
                        <small class="text-muted d-block mt-1">Jika tidak memakai kamera, ketik kode di atas lalu klik Proses.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="scanForm" class="btn btn-primary">Proses</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const form = document.getElementById('scanForm');
        const input = document.getElementById('loan_code');
        if (!form || !input) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const code = input.value.trim();
            if (!code) {
                input.classList.add('is-invalid');
                input.focus();
                return;
            }
            input.classList.remove('is-invalid');
            window.location.href = '/loans/code/' + encodeURIComponent(code);
        });
    })();
</script>
@endsection
