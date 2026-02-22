<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman - Cetak</title>
    <style>
        :root {
            --text: #1b1f24;
            --muted: #6b7280;
            --border: #e5e7eb;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 24px;
            font-family: "Times New Roman", Times, serif;
            color: var(--text);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 16px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
        }
        h1 {
            margin: 0;
            font-size: 20px;
        }
        .meta {
            font-size: 12px;
            color: var(--muted);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid var(--border);
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 11px;
        }
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>Laporan Peminjaman</h1>
            <div class="meta">Perpustakaan Digital</div>
        </div>
        <div class="meta">
            Tanggal cetak: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
        </div>
    </div>

    @if($loans->count())
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama User</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Denda</th>
                </tr>
            </thead>
            <tbody>
            @foreach($loans as $loan)
                @php
                    $status = $loan->status ?? 'dipinjam';
                    $loanDate = $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date) : null;
                    $returnDate = $loan->return_date ? \Carbon\Carbon::parse($loan->return_date) : null;
                    $days = $loanDate ? $loanDate->diffInDays($returnDate ?? now()) : null;
                    $isOverdue = ($loan->display_late_days ?? 0) > 0
                        && !in_array($status, ['dikembalikan', 'terlambat'], true);
                @endphp
                <tr>
                    <td>{{ $loan->loan_code ?? '-' }}</td>
                    <td>{{ optional($loan->user)->full_name ?? '-' }}</td>
                    <td>{{ optional($loan->book)->title ?? '-' }}</td>
                    <td>{{ $loan->loan_date ?? '-' }}</td>
                    <td>{{ $loan->return_date ?? '-' }}</td>
                    <td>{{ $days !== null ? $days . ' hari' : '-' }}</td>
                    <td><span class="badge">{{ $isOverdue ? 'Terlambat' : str_replace('_', ' ', ucfirst($status)) }}</span></td>
                    <td>{{ ($loan->display_fine_amount ?? 0) > 0 ? 'Rp ' . number_format($loan->display_fine_amount, 0, ',', '.') : '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p class="meta">Belum ada data peminjaman.</p>
    @endif

    <script class="no-print">
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
