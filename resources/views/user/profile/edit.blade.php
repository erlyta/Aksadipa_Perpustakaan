@extends('layouts.app')

@section('title','Profil')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/books.css') }}">
<style>
    .profile-wrap {
        max-width: 720px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        font-family: "Space Grotesk", sans-serif;
    }
    .profile-hero {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 18px;
        padding: 12px;
        border-radius: 16px;
        background: linear-gradient(120deg, rgba(30, 60, 114, 0.12), rgba(255, 183, 3, 0.12));
    }
    .avatar {
        width: 92px;
        height: 92px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ffffff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
        background: #f3f4f6;
    }
    .avatar.placeholder {
        display: grid;
        place-items: center;
        font-weight: 700;
        color: #1e3c72;
        background: #e2e8f0;
        text-transform: uppercase;
    }
    .profile-header h2 {
        font-family: "Playfair Display", serif;
        font-size: 26px;
        margin: 0 0 6px;
    }
    .profile-header p {
        color: #667085;
        margin: 0 0 16px;
    }
    .profile-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }
    .profile-actions {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 16px;
    }
    .alert-danger-custom {
        background: #fee2e2;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        border-left: 4px solid #ef4444;
        font-weight: 500;
    }
    @media (max-width: 768px) {
        .profile-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="profile-wrap">
    <div class="profile-header">
        <div class="profile-hero">
            @if(!empty($user->avatar))
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto profil" class="avatar">
            @else
                <div class="avatar placeholder">{{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}</div>
            @endif
            <div>
                <h2 class="hero-title">Profil Saya</h2>
                <p>Perbarui data akun agar peminjaman lebih lancar.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-danger-custom">
            <i class="bi bi-exclamation-circle"></i> Periksa kembali data yang kamu isi.
        </div>
    @endif

    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="profile-grid">
            <div class="form-group">
                <label for="avatar">Foto Profil</label>
                <input
                    type="file"
                    id="avatar"
                    name="avatar"
                    class="form-control @error('avatar') is-invalid @enderror"
                    accept="image/*"
                >
                @error('avatar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="full_name">Nama Lengkap</label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    class="form-control @error('full_name') is-invalid @enderror"
                    value="{{ old('full_name', $user->full_name) }}"
                    required
                >
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username', $user->username) }}"
                    required
                >
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email) }}"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password Baru (opsional)</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Minimal 6 karakter"
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Ulangi password"
                >
            </div>
        </div>

        <div class="profile-actions">
            <button class="btn-success-custom" type="submit">
                <i class="bi bi-check-circle"></i> Simpan Perubahan
            </button>
        </div>
    </form>

    <form action="{{ route('logout') }}" method="POST" class="profile-actions">
        @csrf
        <button class="btn-danger-custom" type="submit">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</div>
@endsection
