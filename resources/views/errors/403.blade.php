<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <style>
        :root {
            --bg: #f6f8fb;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #1e3a8a;
            --danger: #b91c1c;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: radial-gradient(900px 450px at 10% -10%, rgba(30, 58, 138, 0.12), transparent 65%), var(--bg);
            font-family: "Segoe UI", Tahoma, sans-serif;
            color: var(--text);
            padding: 24px;
        }
        .card {
            width: 100%;
            max-width: 560px;
            background: var(--card);
            border-radius: 16px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.1);
            padding: 28px;
            text-align: center;
        }
        .code {
            display: inline-block;
            font-size: 12px;
            font-weight: 700;
            color: var(--danger);
            background: rgba(185, 28, 28, 0.08);
            border-radius: 999px;
            padding: 6px 12px;
            margin-bottom: 10px;
            letter-spacing: .3px;
        }
        h1 {
            margin: 0 0 8px;
            font-size: 28px;
            line-height: 1.2;
        }
        p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }
        .actions {
            margin-top: 22px;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        .btn-light {
            background: #e2e8f0;
            color: #0f172a;
        }
    </style>
</head>
<body>
    @php
        $homeUrl = '/login';
        if (auth()->check()) {
            $homeUrl = auth()->user()->role === 'peminjam' ? '/user/books' : '/dashboard';
        }
    @endphp

    <div class="card">
        <span class="code">ERROR 403</span>
        <h1>Akses Ditolak</h1>
        <p>Anda tidak memiliki izin untuk membuka halaman ini.</p>
        <div class="actions">
            <a class="btn btn-primary" href="{{ $homeUrl }}">Kembali ke Halaman Utama</a>
            <a class="btn btn-light" href="{{ url()->previous() }}">Kembali ke Halaman Sebelumnya</a>
        </div>
    </div>
</body>
</html>
