@extends('layouts.app')

@section('title','Daftar Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">

    <div class="page-header">
        <h2><i class="bi bi-book"></i> Daftar Buku</h2>
        <div class="page-header-actions">
            <a href="{{ route('books.create') }}" class="btn-primary-custom">
                <i class="bi bi-plus-circle"></i> Tambah Buku
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($books->count())
        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}" class="cover-thumb">
                            @else
                                <div class="cover-placeholder">Tanpa Cover</div>
                            @endif
                        </td>
                        <td class="book-title">{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>
                            <span class="book-category">
                                {{ optional($book->category)->name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="stock-badge 
                                {{ $book->stock <= 5 ? 'low' : ($book->stock <= 15 ? 'medium' : 'high') }}">
                                {{ $book->stock }} copies
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('books.edit', $book->id) }}" class="btn-warning-custom">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('books.destroy', $book->id) }}" 
                                      method="POST" 
                                      data-confirm-title="Hapus Buku"
                                      data-confirm-message="Yakin ingin menghapus buku ini?"
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
            <h3>Belum ada buku</h3>
            <p>Mulai tambahkan buku ke perpustakaan digital Anda</p>
        </div>
    @endif

</div>
@endsection
