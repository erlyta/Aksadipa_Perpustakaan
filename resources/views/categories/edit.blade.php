@extends('layouts.app')

@section('title','Edit Kategori')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
<style>
    .category-edit-hero {
        background: linear-gradient(135deg, #0f172a, #1e3a8a 55%, #1d4ed8);
        color: #f8fafc;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }
    .category-edit-hero::after {
        content: "";
        position: absolute;
        width: 210px;
        height: 210px;
        border-radius: 50%;
        top: -80px;
        right: -70px;
        background: rgba(255, 255, 255, 0.12);
    }
    body.role-petugas .category-edit-hero {
        background: linear-gradient(135deg, #134e4a, #0f766e 58%, #0d9488);
    }
    .category-pill {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .2px;
        background: rgba(255, 255, 255, 0.2);
        margin-bottom: 10px;
    }
    .helper-card {
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 16px;
        background: #ffffff;
        margin-bottom: 18px;
    }
    .helper-card h4 {
        margin: 0 0 8px;
        font-size: 16px;
    }
    .helper-card p {
        margin: 0;
        color: var(--muted);
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="books-container">
    <div class="category-edit-hero">
        <div class="position-relative" style="z-index:1;">
            <span class="category-pill">
                {{ auth()->check() && auth()->user()->role === 'petugas' ? 'Mode Petugas' : 'Mode Admin' }}
            </span>
            <h2 class="mb-1"><i class="bi bi-pencil-square"></i> Edit Kategori</h2>
            <p class="mb-0 opacity-75">Perbarui nama kategori agar katalog tetap rapi dan mudah dicari.</p>
        </div>
    </div>

    <div class="form-container">
        @if(auth()->check() && auth()->user()->role === 'petugas')
            <div class="helper-card">
                <h4><i class="bi bi-lightbulb"></i> Tips Petugas</h4>
                <p>Gunakan nama kategori singkat, jelas, dan konsisten agar pencarian buku lebih cepat.</p>
            </div>
        @endif

        <div class="form-header">
            <div class="header-icon">
                <i class="bi bi-tag"></i>
            </div>
            <div>
                <h3>Ubah Informasi Kategori</h3>
                <p>Pastikan nama baru tidak membingungkan pengguna.</p>
            </div>
        </div>

        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama Kategori <span style="color: #ef4444;">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    placeholder="Contoh: Fiksi, Sains, Sejarah"
                    required
                    autofocus
                >
                @error('name')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle"></i> Update Kategori
                </button>
                <a href="{{ route('categories.index') }}" class="btn-cancel">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
