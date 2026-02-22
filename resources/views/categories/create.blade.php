@extends('layouts.app')

@section('title','Tambah Kategori')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">
    <div class="page-header">
        <div>
            <h2><i class="bi bi-tags"></i> Tambah Kategori</h2>
            <div class="text-muted mt-1">Buat kategori agar koleksi lebih terstruktur.</div>
        </div>
    </div>

    <div class="form-container">
        <div class="form-header">
            <div class="header-icon">
                <i class="bi bi-tag"></i>
            </div>
            <div>
                <h3>Informasi Kategori</h3>
                <p>Isi nama kategori dengan jelas dan singkat.</p>
            </div>
        </div>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Kategori <span style="color: #ef4444;">*</span></label>
                <input type="text" id="name" name="name" placeholder="Contoh: Fiksi, Sains, Sejarah" required>
                @error('name')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle"></i> Simpan Kategori
                </button>
                <a href="{{ route('categories.index') }}" class="btn-cancel">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
