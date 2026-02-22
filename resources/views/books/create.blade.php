@extends('layouts.app')

@section('title','Tambah Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">
    <div class="page-header">
        <div>
            <h2>Tambah Buku Baru</h2>
            <div class="text-muted mt-1">Lengkapi detail buku agar koleksi tetap rapi dan mudah dicari.</div>
        </div>
    </div>

    <div class="form-container">
        <div class="form-header">
            <div class="header-icon">
                <i class="bi bi-bookmark-plus"></i>
            </div>
            <div>
                <h3>Informasi Buku</h3>
                <p>Bidang bertanda <span style="color: #ef4444;">*</span> wajib diisi.</p>
            </div>
        </div>
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Judul Buku <span style="color: #ef4444;">*</span></label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    placeholder="Masukkan judul buku"
                    value="{{ old('title') }}"
                    required
                >
                @error('title')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="author">Penulis <span style="color: #ef4444;">*</span></label>
                <input 
                    type="text" 
                    id="author" 
                    name="author" 
                    placeholder="Masukkan nama penulis"
                    value="{{ old('author') }}"
                    required
                >
                @error('author')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id">Kategori <span style="color: #ef4444;">*</span></label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('category_id')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="publisher">Penerbit</label>
                <input 
                    type="text" 
                    id="publisher" 
                    name="publisher" 
                    placeholder="Masukkan nama penerbit"
                    value="{{ old('publisher') }}"
                >
                @error('publisher')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input 
                    type="text" 
                    id="isbn" 
                    name="isbn" 
                    placeholder="Masukkan nomor ISBN"
                    value="{{ old('isbn') }}"
                >
                @error('isbn')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="year_published">Tahun Terbit <span style="color: #ef4444;">*</span></label>
                <input 
                    type="number" 
                    id="year_published" 
                    name="year_published" 
                    placeholder="Masukkan tahun terbit"
                    value="{{ old('year_published') }}"
                    required
                    min="1900"
                    max="{{ date('Y') }}"
                >
                @error('year_published')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="stock">Stok <span style="color: #ef4444;">*</span></label>
                <input 
                    type="number" 
                    id="stock" 
                    name="stock" 
                    placeholder="Masukkan jumlah stok"
                    value="{{ old('stock', 1) }}"
                    required
                    min="0"
                >
                @error('stock')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea 
                    id="description" 
                    name="description" 
                    placeholder="Masukkan deskripsi buku (opsional)"
                >{{ old('description') }}</textarea>
                @error('description')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="cover">Cover Buku</label>
                <input 
                    type="file" 
                    id="cover" 
                    name="cover" 
                    accept="image/*"
                >
                <small class="text-muted d-block mt-1">Format JPG/PNG, maks 2MB.</small>
                @error('cover')
                    <small style="color: #ef4444; display: block; margin-top: 4px;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle"></i> Simpan Buku
                </button>
                <a href="{{ route('books.index') }}" class="btn-cancel">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
