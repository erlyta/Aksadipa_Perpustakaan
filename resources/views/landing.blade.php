<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aksadipa | Perpustakaan Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #0f172a;
            --muted: #5b677a;
            --brand: #1e3c72;
            --brand-2: #ffb703;
            --surface: #ffffff;
            --bg: #eef2f7;
            --card-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        }

        body {
            background:
                radial-gradient(1200px 600px at 85% -20%, rgba(30, 60, 114, 0.12), transparent 70%),
                radial-gradient(900px 500px at -10% 10%, rgba(255, 183, 3, 0.12), transparent 65%),
                var(--bg);
            color: var(--ink);
            font-family: "Space Grotesk", system-ui, -apple-system, sans-serif;
        }

        .navbar {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .brand-name {
            font-family: "Playfair Display", serif;
            letter-spacing: 0.5px;
        }

        .hero {
            position: relative;
            overflow: hidden;
            color: #f8fafc;
            min-height: 78vh;
            padding: 120px 20px 140px;
            background: linear-gradient(135deg, #0f172a, #1e3c72 55%, #264a8a);
            border-bottom-left-radius: 36px;
            border-bottom-right-radius: 36px;
        }

        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            opacity: 0.2;
        }

        .hero::before {
            background: #ffb703;
            top: -120px;
            right: -140px;
        }

        .hero::after {
            background: #60a5fa;
            bottom: -160px;
            left: -140px;
        }

        .hero h1 {
            font-family: "Playfair Display", serif;
            font-weight: 700;
            font-size: clamp(2.4rem, 4vw, 3.6rem);
        }

        .hero p {
            opacity: 0.92;
            font-size: 1.1rem;
            color: #e2e8f0;
        }

        .hero .lead {
            max-width: 700px;
            margin: 18px auto 0;
        }

        .hero-actions .btn {
            border-radius: 999px;
            padding: 12px 26px;
            font-weight: 600;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: var(--card-shadow);
            border-radius: 20px;
        }

        .feature-card {
            border: none;
            border-radius: 18px;
            box-shadow: var(--card-shadow);
            transition: transform .3s ease, box-shadow .3s ease;
            background: var(--surface);
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.12);
        }

        .icon-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(30, 60, 114, 0.1);
            color: var(--brand);
            font-weight: 700;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--brand);
        }

        .section-title {
            font-family: "Playfair Display", serif;
        }

        .cta {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .site-footer {
            background: #0f172a;
            color: #e2e8f0;
        }

        .site-footer a {
            color: #cbd5f5;
            text-decoration: none;
        }

        .site-footer a:hover {
            color: #ffffff;
        }

        .footer-title {
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .fade-up {
            animation: fadeUp 0.8s ease both;
        }

        .delay-1 { animation-delay: .1s; }
        .delay-2 { animation-delay: .2s; }
        .delay-3 { animation-delay: .3s; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark px-4 py-3">
    <span class="navbar-brand fw-bold brand-name">&#128214; Aksadipa</span>
    <a href="/login" class="btn btn-outline-light rounded-pill px-4">Login</a>
</nav>

<!-- Hero -->
<section class="hero">
    <div class="container text-center position-relative">
        <div class="fade-up">
            <h1>Perpustakaan Digital yang Rapi, Cepat, dan Nyaman</h1>
            <p class="lead">
                Aksadipa membantu siswa dan pustakawan mengelola peminjaman buku secara offline
                dengan tampilan modern, alur yang jelas, dan kontrol stok yang rapi.
            </p>
        </div>
        <div class="hero-actions mt-4 fade-up delay-1">
            <a href="/login" class="btn btn-light text-primary me-2">Login untuk Meminjam</a>
            <a href="#fitur" class="btn btn-outline-light">Lihat Fitur</a>
        </div>
        <div class="mt-5 d-flex justify-content-center gap-3 flex-wrap fade-up delay-2">
            <div class="glass-card px-4 py-3">
                <div class="stat-number">1000+</div>
                <div class="text-muted">Buku Tersusun</div>
            </div>
            <div class="glass-card px-4 py-3">
                <div class="stat-number">3 Menit</div>
                <div class="text-muted">Rata-rata Transaksi</div>
            </div>
            <div class="glass-card px-4 py-3">
                <div class="stat-number">Offline</div>
                <div class="text-muted">Tetap Stabil</div>
            </div>
        </div>
    </div>
</section>

<!-- Info Section -->
<section id="fitur" class="container my-5">
    <div class="text-center mb-5">
        <p class="text-uppercase text-muted fw-semibold mb-2">Fitur Unggulan</p>
        <h2 class="section-title">Semua yang Kamu Butuhkan untuk Peminjaman Cepat</h2>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card feature-card p-4 h-100">
                <div class="icon-pill mb-3">&#128214;</div>
                <h4>Koleksi Tersusun</h4>
                <p class="text-muted mb-0">
                    Buku pelajaran dan bacaan tertata rapi dengan informasi stok yang jelas.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card p-4 h-100">
                <div class="icon-pill mb-3">&#9201;</div>
                <h4>Transaksi Praktis</h4>
                <p class="text-muted mb-0">
                    Proses pinjam dan kembali lebih singkat, tanpa antre lama.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card feature-card p-4 h-100">
                <div class="icon-pill mb-3">&#127979;</div>
                <h4>Offline Stabil</h4>
                <p class="text-muted mb-0">
                    Tetap berjalan tanpa internet, cocok untuk perpustakaan sekolah.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="container my-5">
    <div class="row g-4 align-items-center">
        <div class="col-lg-6">
            <div class="glass-card p-4 p-lg-5">
                <h3 class="section-title mb-3">Alur Sederhana, Hasil Maksimal</h3>
                <p class="text-muted mb-4">
                    Pengguna cukup login, pilih buku, lalu konfirmasi peminjaman. Pustakawan
                    dapat memantau status peminjaman dan stok secara real-time.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Login & Pilih</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Konfirmasi</span>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Ambil Buku</span>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="cta p-4 p-lg-5">
                <h3 class="section-title mb-3">Siap Dipakai Hari Ini</h3>
                <p class="text-muted mb-4">
                    Aksadipa dirancang agar mudah dipahami oleh siswa maupun staf perpustakaan.
                </p>
                <a href="/login" class="btn btn-primary rounded-pill px-4">Mulai Sekarang</a>
            </div>
        </div>
    </div>
</section>

<footer class="site-footer mt-5 pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-title mb-2">&#128214; Aksadipa</div>
                <p class="mb-3">
                    Perpustakaan digital sekolah yang membantu proses peminjaman dan pengelolaan
                    koleksi secara cepat, rapi, dan stabil tanpa internet.
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Offline Ready</span>
                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">Mudah Dipakai</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="footer-title mb-2">Kontak</div>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">Email: <a href="mailto:perpustakaan@sekolah.id">perpustakaan@sekolah.id</a></li>
                    <li class="mb-2">Telepon: <a href="tel:+620000000000">+62 896 6777 7318</a></li>
                    <li>Alamat: Jl. Pendidikan No. 1, Kota Anda</li>
                </ul>
            </div>
            <div class="col-lg-4">
                <div class="footer-title mb-2">Jam Layanan</div>
                <ul class="list-unstyled mb-3">
                    <li class="mb-2">Senin - Jumat: 07.30 - 15.30</li>
                    <li>Sabtu: 08.00 - 12.00</li>
                </ul>
                <div class="footer-title mb-2">Tautan Cepat</div>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="/login">Login</a></li>
                    <li><a href="#fitur">Fitur</a></li>
                </ul>
            </div>
        </div>
        <hr class="border-light border-opacity-25 my-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <small>© 2026 Aksadipa. Perpustakaan Digital Sekolah.</small>
            <small>Dirancang untuk literasi yang lebih baik.</small>
        </div>
    </div>
</footer>

</body>
</html>
