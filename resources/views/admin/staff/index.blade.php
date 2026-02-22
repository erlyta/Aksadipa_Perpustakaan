@extends('layouts.app')

@section('title','Akun Petugas')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
@endpush

@section('content')
<div class="books-container">
    <div class="page-header">
        <div>
            <h2><i class="bi bi-person-badge"></i> Akun Petugas</h2>
            <div class="text-muted mt-1">Tambah dan kelola akun petugas perpustakaan.</div>
        </div>
        <div class="page-header-actions">
            <button class="btn-primary-custom" type="button" data-bs-toggle="collapse" data-bs-target="#formPetugas" aria-expanded="false" aria-controls="formPetugas">
                <i class="bi bi-person-plus"></i> Tambah Petugas
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="collapse" id="formPetugas">
        <div class="form-container mb-4">
            <div class="form-header">
                <div class="header-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <div>
                    <h3>Tambah Petugas</h3>
                    <p>Bidang bertanda <span style="color: #ef4444;">*</span> wajib diisi.</p>
                </div>
            </div>

            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="full_name">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        placeholder="Masukkan nama lengkap"
                        value="{{ old('full_name') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email <span style="color: #ef4444;">*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Masukkan email"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password <span style="color: #ef4444;">*</span></label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 6 karakter"
                        required
                    >
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle"></i> Simpan Petugas
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($staff->count())
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
                @foreach($staff as $user)
                    <tr>
                        <td class="book-title">{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->username }}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-warning-custom" type="button" data-bs-toggle="modal" data-bs-target="#editStaffModal{{ $user->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('admin.staff.destroy', $user->id) }}"
                                      method="POST"
                                      data-confirm-title="Hapus Petugas"
                                      data-confirm-message="Yakin ingin menghapus petugas ini?"
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

                    <div class="modal fade" id="editStaffModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Petugas</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.staff.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group mb-3">
                                            <label for="full_name_{{ $user->id }}">Nama Lengkap</label>
                                            <input
                                                type="text"
                                                id="full_name_{{ $user->id }}"
                                                name="full_name"
                                                class="form-control"
                                                value="{{ old('full_name', $user->full_name) }}"
                                                required
                                            >
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email_{{ $user->id }}">Email</label>
                                            <input
                                                type="email"
                                                id="email_{{ $user->id }}"
                                                name="email"
                                                class="form-control"
                                                value="{{ old('email', $user->email) }}"
                                                required
                                            >
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="password_{{ $user->id }}">Password (opsional)</label>
                                            <input
                                                type="password"
                                                id="password_{{ $user->id }}"
                                                name="password"
                                                class="form-control"
                                                placeholder="Kosongkan jika tidak diubah"
                                            >
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>Belum ada petugas</h3>
            <p>Tambahkan petugas agar pengelolaan perpustakaan lebih efektif.</p>
        </div>
    @endif
</div>
@endsection
