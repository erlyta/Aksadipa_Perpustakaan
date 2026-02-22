@extends('layouts.app')

@section('title','Kategori')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">
    <div class="page-header">
        <div>
            <h2><i class="bi bi-tags"></i> Kategori</h2>
            <div class="text-muted mt-1">Kelola kategori agar koleksi lebih terstruktur.</div>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('categories.create') }}" class="btn-primary-custom">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($categories->count())
        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td class="book-title">{{ $category->name }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('categories.edit',$category) }}" class="btn-warning-custom">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('categories.destroy',$category) }}" method="POST" style="display:inline"
                                    data-confirm-title="Hapus Kategori"
                                    data-confirm-message="Yakin hapus kategori?"
                                    data-confirm-button-text="Ya, Hapus">
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
            <h3>Belum ada kategori</h3>
            <p>Tambahkan kategori untuk mengelompokkan koleksi buku.</p>
        </div>
    @endif
</div>
@endsection
