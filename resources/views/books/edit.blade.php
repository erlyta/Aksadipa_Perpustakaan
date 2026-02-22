@extends('layouts.app')

@section('title','Edit Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">
    <div class="page-header">
        <div>
            <h2>Edit Buku</h2>
            <div class="text-muted mt-1">Perbarui informasi buku agar data tetap akurat.</div>
        </div>
    </div>

    <div class="form-container">
        <div class="form-header">
            <div class="header-icon">
                <i class="bi bi-book"></i>
            </div>
            <div>
                <h3>Detail Buku</h3>
                <p>Bidang bertanda <span style="color: #ef4444;">*</span> wajib diisi.</p>
            </div>
        </div>

        <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Judul Buku <span style="color: #ef4444;">*</span></label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Masukkan judul buku"
                    value="{{ old('title', $book->title) }}"
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
                    value="{{ old('author', $book->author) }}"
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
                            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
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
                    value="{{ old('publisher', $book->publisher) }}"
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
                    value="{{ old('isbn', $book->isbn ?? '') }}"
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
                    value="{{ old('year_published', $book->year) }}"
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
                    value="{{ old('stock', $book->stock) }}"
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
                >{{ old('description', $book->synopsis) }}</textarea>
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

                @if($book->cover)
                    <div class="mt-3">
                        <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="cover-preview">
                    </div>
                @endif
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                </button>
                <a href="{{ route('books.index') }}" class="btn-cancel">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
