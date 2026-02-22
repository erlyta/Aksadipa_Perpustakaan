<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Perpustakaan Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #0f172a;
            --muted: #5b677a;
            --brand: #1e3c72;
            --brand-2: #ffb703;
            --surface: #ffffff;
        }

        body {
            font-family: "Space Grotesk", system-ui, -apple-system, sans-serif;
            background:
                radial-gradient(900px 500px at 10% -10%, rgba(255, 183, 3, 0.12), transparent 65%),
                radial-gradient(1000px 600px at 90% 0%, rgba(30, 60, 114, 0.12), transparent 70%),
                #eef2f7;
            color: var(--ink);
        }

        .auth-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .auth-card {
            width: min(920px, 100%);
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 70px rgba(15, 23, 42, 0.12);
            background: var(--surface);
        }

        .auth-hero {
            background: linear-gradient(135deg, #0f172a, #1e3c72 55%, #264a8a);
            color: #f8fafc;
            padding: 48px;
            position: relative;
        }

        .auth-hero::before {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255, 183, 3, 0.2);
            top: -80px;
            right: -80px;
        }

        .auth-hero h1 {
            font-family: "Playfair Display", serif;
            font-weight: 700;
        }

        .auth-hero p {
            color: #e2e8f0;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            font-weight: 600;
        }

        .form-side {
            padding: 48px;
        }

        .title {
            font-weight: 600;
        }

        .subtitle {
            font-size: 14px;
            color: var(--muted);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #94a3b8;
            box-shadow: 0 0 0 0.2rem rgba(30, 60, 114, 0.12);
        }

        .btn-primary {
            border-radius: 999px;
            padding: 12px;
            font-weight: 600;
            background: var(--brand);
            border-color: var(--brand);
        }

        .btn-outline-secondary {
            border-radius: 999px;
            padding: 12px;
            font-weight: 600;
        }

        .small-link {
            font-size: 14px;
        }

        .fade-up {
            animation: fadeUp 0.8s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 991.98px) {
            .auth-hero {
                padding: 32px;
            }
            .form-side {
                padding: 32px;
            }
        }
    </style>
</head>
<body>

<div class="auth-wrap">
    <div class="card auth-card">
        <div class="row g-0">
            <div class="col-lg-5 auth-hero">
                <div class="fade-up">
                    <div class="brand-pill mb-4">&#128214; Aksadipa</div>
                    <h1 class="mb-3">Selamat Datang Kembali</h1>
                    <p class="mb-4">
                        Masuk untuk mengelola peminjaman, memantau stok, dan menjaga perpustakaan tetap rapi.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">Cepat</span>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">Offline</span>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">Terstruktur</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 form-side">
                <div class="text-center mb-4">
                    <h4 class="title">Login Akun</h4>
                    <p class="subtitle">Silakan login untuk melanjutkan</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>

                        <a href="{{ route('landing') }}" class="btn btn-outline-secondary w-100">
                            Batal
                        </a>
                    </div>
                </form>

                <div class="text-center mt-3 small-link">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                        Daftar sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
