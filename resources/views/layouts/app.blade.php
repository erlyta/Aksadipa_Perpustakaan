<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Aksadipa')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Base Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .peminjam-shell {
            min-height: 100vh;
            --brand: #1e3c72;
            --brand-dark: #0f172a;
            --brand-hover: #17325e;
            --brand-soft: rgba(30, 60, 114, 0.18);
            --ink: #0f172a;
            --muted: #5b677a;
            background:
                radial-gradient(1200px 600px at 85% -20%, rgba(30, 60, 114, 0.22), transparent 70%),
                radial-gradient(900px 500px at -10% 10%, rgba(30, 60, 114, 0.16), transparent 65%),
                #e8eef8;
            font-family: "Space Grotesk", system-ui, -apple-system, sans-serif;
            color: var(--ink);
        }
        .peminjam-shell h1,
        .peminjam-shell h2,
        .peminjam-shell h3,
        .peminjam-shell h4,
        .peminjam-shell h5 {
            font-family: "Playfair Display", serif;
            letter-spacing: -0.2px;
        }
        .peminjam-shell .hero-title {
            display: inline-block;
            font-size: clamp(1.8rem, 3.6vw, 2.6rem);
            line-height: 1.1;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 8px;
            background: linear-gradient(120deg, #0f172a 0%, #1e3c72 62%, #365fa3 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
            text-wrap: balance;
        }
        .peminjam-shell .hero-title::after {
            content: "";
            display: block;
            width: min(140px, 52%);
            height: 6px;
            border-radius: 999px;
            margin-top: 8px;
            background: linear-gradient(90deg, #ffb703, rgba(255, 183, 3, 0.2));
        }
        .peminjam-shell .user-hero .hero-title {
            background: none;
            color: #f8fafc;
            text-shadow: 0 6px 18px rgba(15, 23, 42, 0.3);
        }
        .peminjam-shell .user-hero .hero-title::after {
            background: linear-gradient(90deg, #ffb703, rgba(255, 183, 3, 0.25));
        }
        .peminjam-content {
            padding: 20px 18px 88px;
        }
        .bottom-nav {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.94);
            border-top: 1px solid rgba(15, 23, 42, 0.08);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
            padding: 10px 12px 14px;
            box-shadow: 0 -12px 30px rgba(15, 23, 42, 0.12);
            z-index: 1000;
            backdrop-filter: blur(8px);
        }
        .bottom-nav a,
        .bottom-nav a:link,
        .bottom-nav a:visited {
            text-decoration: none;
            color: var(--muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            transition: color .2s ease;
        }
        .bottom-nav a:hover {
            color: var(--brand);
            text-decoration: none;
        }
        .bottom-nav a.active {
            color: var(--brand);
        }
        .bottom-nav .nav-icon {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: var(--brand-soft);
            font-size: 18px;
        }
        .bottom-nav a.active .nav-icon {
            background: rgba(30, 60, 114, 0.28);
            color: var(--brand);
        }
        @media (min-width: 992px) {
            .bottom-nav {
                max-width: 520px;
                left: 50%;
                transform: translateX(-50%);
                border-radius: 18px 18px 0 0;
            }
            .peminjam-content {
                padding-bottom: 110px;
            }
        }
    </style>

    <!-- Page Specific CSS -->
    @stack('styles')
</head>

<body class="{{ auth()->check() ? 'role-' . auth()->user()->role : 'role-guest' }}">
@if(auth()->check() && auth()->user()->role === 'peminjam')
<div class="peminjam-shell">
    <main class="peminjam-content">
        @yield('content')
    </main>

    <nav class="bottom-nav">
        <a href="{{ url('/user/books') }}" class="{{ request()->is('user/books*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-book"></i></span>
            <span>Katalog</span>
        </a>
        <a href="{{ url('/user/favorites') }}" class="{{ request()->is('user/favorites*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-heart"></i></span>
            <span>Koleksi</span>
        </a>
        <a href="{{ url('/user/loans') }}" class="{{ request()->is('user/loans*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-clock-history"></i></span>
            <span>Riwayat</span>
        </a>
        <a href="{{ url('/user/profile') }}" class="{{ request()->is('user/profile') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-person"></i></span>
            <span>Profil</span>
        </a>
    </nav>
</div>
@else
<div class="app-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="sidebar-logo">
            <img src="{{ asset('image/aksadipa.png') }}" alt="Aksadipa Logo" class="logo-img">
            <span class="logo-text">Aksadipa</span>
        </div>

        <ul class="sidebar-menu">
            @if(auth()->check() && auth()->user()->role === 'peminjam')
                <li class="{{ request()->is('user/books*') ? 'active' : '' }}">
                    <a href="{{ url('/user/books') }}">
                        <i class="bi bi-book"></i>
                        <span>Katalog</span>
                    </a>
                </li>
                <li class="{{ request()->is('user/favorites') ? 'active' : '' }}">
                    <a href="{{ url('/user/favorites') }}">
                        <i class="bi bi-heart"></i>
                        <span>Koleksi Saya</span>
                    </a>
                </li>
                <li class="{{ request()->is('user/loans') ? 'active' : '' }}">
                    <a href="{{ url('/user/loans') }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat</span>
                    </a>
                </li>
            @else
                <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dasbor</span>
                    </a>
                </li>

                <li class="{{ request()->is('books*') ? 'active' : '' }}">
                    <a href="{{ url('/books') }}">
                        <i class="bi bi-book"></i>
                        <span>Buku</span>
                    </a>
                </li>

                <li class="{{ request()->is('categories*') ? 'active' : '' }}">
                    <a href="{{ url('/categories') }}">
                        <i class="bi bi-tags"></i>
                        <span>Kategori</span>
                    </a>
                </li>

                <li class="{{ request()->is('loans*') ? 'active' : '' }}">
                    <a href="{{ url('/loans') }}">
                        <i class="bi bi-arrow-left-right"></i>
                        <span>Peminjaman</span>
                    </a>
                </li>

                @if(auth()->check() && auth()->user()->role === 'admin')
                <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/users') }}">
                        <i class="bi bi-people"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
                <li class="{{ request()->is('admin/staff*') ? 'active' : '' }}">
                    <a href="{{ url('/admin/staff') }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Petugas</span>
                    </a>
                </li>
                @endif
                <li class="{{ request()->is('reports*') ? 'active' : '' }}">
                    <a href="{{ route('reports.loans') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            @endif
        </ul>

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </button>
        </form>

    </aside>

    <!-- CONTENT -->
    <main class="content">
        @yield('content')
    </main>

</div>
@endif

<div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmActionTitle">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="confirmActionMessage">Apakah Anda yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmActionButton">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (for collapse, dropdown, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const confirmModalEl = document.getElementById('confirmActionModal');
        if (!confirmModalEl || typeof bootstrap === 'undefined') {
            return;
        }

        const modalTitleEl = document.getElementById('confirmActionTitle');
        const modalMessageEl = document.getElementById('confirmActionMessage');
        const confirmButtonEl = document.getElementById('confirmActionButton');
        const confirmModal = new bootstrap.Modal(confirmModalEl);
        let pendingForm = null;

        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!(form instanceof HTMLFormElement)) {
                return;
            }

            if (!form.hasAttribute('data-confirm-message')) {
                return;
            }

            if (form.dataset.confirmed === 'true') {
                delete form.dataset.confirmed;
                return;
            }

            event.preventDefault();

            const title = form.dataset.confirmTitle || 'Konfirmasi';
            const message = form.dataset.confirmMessage || 'Apakah Anda yakin ingin melanjutkan?';
            const confirmText = form.dataset.confirmButtonText || 'Ya, Lanjutkan';

            modalTitleEl.textContent = title;
            modalMessageEl.textContent = message;
            confirmButtonEl.textContent = confirmText;
            pendingForm = form;

            confirmModal.show();
        });

        confirmButtonEl.addEventListener('click', function () {
            if (!pendingForm) {
                return;
            }

            pendingForm.dataset.confirmed = 'true';
            confirmModal.hide();
            pendingForm.submit();
            pendingForm = null;
        });

        confirmModalEl.addEventListener('hidden.bs.modal', function () {
            pendingForm = null;
        });
    })();
</script>
</body>
</html>
