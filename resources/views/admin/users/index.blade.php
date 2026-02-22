@extends('layouts.app')

@section('title','Akun User')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">
    <div class="page-header">
        <div>
            <h2><i class="bi bi-people"></i> Akun User</h2>
            <div class="text-muted mt-1">Daftar akun peminjam yang terdaftar.</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($users->count())
        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="book-title">{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->username }}</td>
                        <td>
                            <div class="action-buttons">
                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                      method="POST"
                                      data-confirm-title="Hapus User"
                                      data-confirm-message="Yakin ingin menghapus user ini?"
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
            <h3>Belum ada user</h3>
            <p>User (peminjam) akan muncul di sini setelah melakukan registrasi.</p>
        </div>
    @endif
</div>
@endsection
