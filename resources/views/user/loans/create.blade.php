@extends('layouts.app')

@section('title','Ajukan Peminjaman')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">
    <div class="page-header">
        <div>
            <h2 class="hero-title"><i class="bi bi-journal-plus"></i> Ajukan Peminjaman</h2>
            <div class="text-muted mt-1">Isi form peminjaman, lalu tunggu konfirmasi admin/petugas.</div>
        </div>
    </div>

    @if(session('loan_code'))
        <div class="alert-success-custom">
            <i class="bi bi-check-circle"></i>
            Permintaan berhasil. Kode kamu:
            <strong>{{ session('loan_code') }}</strong>
            (Tunjukkan ke petugas untuk konfirmasi)
        </div>
    @endif

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-info-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="form-container">
        <div class="form-header">
            <div class="header-icon">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div>
                <h3>Form Peminjaman</h3>
                <p>Bidang bertanda <span style="color: #ef4444;">*</span> wajib diisi.</p>
            </div>
        </div>

        <form action="{{ route('user.loans.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Judul Buku <span style="color: #ef4444;">*</span></label>
                @if($selectedBook)
                    <input type="text" class="form-control" value="{{ $selectedBook->title }}" readonly>
                    <input type="hidden" name="book_id" value="{{ $selectedBook->id }}">
                @else
                    <select id="book_id" name="book_id" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ (old('book_id') ?? $selectedBookId) == $book->id ? 'selected' : '' }}>
                                {{ $book->title }} (stok: {{ $book->stock }})
                            </option>
                        @endforeach
                    </select>
                @endif
                @error('book_id')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Dipinjam oleh</label>
                <input type="text" class="form-control" value="{{ auth()->user()->full_name }}" readonly>
            </div>

            <div class="form-group">
                <label for="loan_date">Tanggal Pinjam <span style="color: #ef4444;">*</span></label>
                <input
                    type="date"
                    id="loan_date"
                    name="loan_date"
                    value="{{ old('loan_date', now()->toDateString()) }}"
                    required
                >
                @error('loan_date')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Jam Mulai</label>
                <input
                    type="text"
                    class="form-control"
                    value="Otomatis diisi saat admin/petugas konfirmasi"
                    readonly
                >
                <small class="text-muted d-block mt-1">Jam konfirmasi dicatat sistem, tapi masa pinjam tetap dihitung per hari.</small>
            </div>

            <div class="form-group">
                <label for="duration_unit">Jenis Durasi <span style="color: #ef4444;">*</span></label>
                <input type="hidden" id="duration_unit" name="duration_unit" value="day">
                <input type="text" class="form-control" value="Per Hari" readonly>
                @error('duration_unit')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="duration_value" id="duration_value_label">Durasi (hari) <span style="color: #ef4444;">*</span></label>
                <input
                    type="number"
                    id="duration_value"
                    name="duration_value"
                    min="1"
                    max="365"
                    value="{{ old('duration_value', 7) }}"
                    required
                >
                @error('duration_value')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-send-check"></i> Ajukan Peminjaman
                </button>
                <a href="{{ route('user.books.index') }}" class="btn-cancel">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
