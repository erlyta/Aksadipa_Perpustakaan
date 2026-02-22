@extends('layouts.app')

@section('title','Tambah Peminjaman')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">
    <div class="page-header">
        <div>
            <h2><i class="bi bi-arrow-left-right"></i> Tambah Peminjaman</h2>
            <div class="text-muted mt-1">Catat peminjaman buku oleh user.</div>
        </div>
    </div>

    <div class="form-container">
        <div class="form-header">
            <div class="header-icon">
                <i class="bi bi-journal-plus"></i>
            </div>
            <div>
                <h3>Data Peminjaman</h3>
                <p>Bidang bertanda <span style="color: #ef4444;">*</span> wajib diisi.</p>
            </div>
        </div>

        <form action="{{ route('loans.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="loan_code">Kode Peminjaman</label>
                <input
                    type="text"
                    id="loan_code"
                    name="loan_code"
                    placeholder="Opsional, akan dibuat otomatis"
                    value="{{ old('loan_code') }}"
                >
                @error('loan_code')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="user_id">User <span style="color: #ef4444;">*</span></label>
                <select id="user_id" name="user_id" required>
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->full_name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="book_id">Buku <span style="color: #ef4444;">*</span></label>
                <select id="book_id" name="book_id" required>
                    <option value="">-- Pilih Buku --</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }}
                        </option>
                    @endforeach
                </select>
                @error('book_id')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="loan_date">Tanggal Pinjam <span style="color: #ef4444;">*</span></label>
                <input
                    type="date"
                    id="loan_date"
                    name="loan_date"
                    value="{{ old('loan_date') }}"
                    required
                >
                @error('loan_date')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="return_date">Tanggal Kembali</label>
                <input
                    type="date"
                    id="return_date"
                    name="return_date"
                    value="{{ old('return_date') }}"
                >
                @error('return_date')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="menunggu" {{ old('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ old('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                </select>
                @error('status')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle"></i> Simpan Peminjaman
                </button>
                <a href="{{ route('loans.index') }}" class="btn-cancel">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
