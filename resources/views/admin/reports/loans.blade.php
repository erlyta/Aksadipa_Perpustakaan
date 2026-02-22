@extends('layouts.app')

@section('title','Laporan Peminjaman')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">
    <div class="page-header">
        <div>
            <h2><i class="bi bi-clipboard-data"></i> Laporan Peminjaman</h2>
            <div class="text-muted mt-1">Rekap peminjaman buku untuk admin dan petugas.</div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('reports.loans.excel') }}" class="btn-primary-custom">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </a>
            <a href="{{ route('reports.loans.print') }}" target="_blank" class="btn-primary-custom">
                <i class="bi bi-printer"></i> Cetak
            </a>
        </div>
    </div>

    @if($loans->count())
        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama User</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($loans as $loan)
                    @php
                        $status = $loan->status ?? 'dipinjam';
                        $loanDate = $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date) : null;
                        $returnDate = $loan->return_date ? \Carbon\Carbon::parse($loan->return_date) : null;
                        $days = $loanDate ? $loanDate->diffInDays($returnDate ?? now()) : null;
                        $isOverdue = ($loan->display_late_days ?? 0) > 0
                            && !in_array($status, ['dikembalikan', 'terlambat'], true);
                    @endphp
                    <tr>
                        <td class="book-title">{{ $loan->loan_code ?? '-' }}</td>
                        <td>{{ optional($loan->user)->full_name ?? '-' }}</td>
                        <td>{{ optional($loan->book)->title ?? '-' }}</td>
                        <td>{{ $loan->loan_date ?? '-' }}</td>
                        <td>{{ $loan->return_date ?? '-' }}</td>
                        <td>{{ $days !== null ? $days . ' hari' : '-' }}</td>
                        <td>
                            <span class="book-category {{ $isOverdue ? 'overdue' : '' }}">
                                {{ $isOverdue ? 'Terlambat' : str_replace('_', ' ', ucfirst($status)) }}
                            </span>
                        </td>
                        <td>{{ ($loan->display_fine_amount ?? 0) > 0 ? 'Rp ' . number_format($loan->display_fine_amount, 0, ',', '.') : '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>Belum ada data</h3>
            <p>Data peminjaman akan muncul di sini.</p>
        </div>
    @endif
</div>
@endsection
