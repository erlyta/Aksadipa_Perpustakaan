<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th colspan="10">Laporan Peminjaman - Perpustakaan Digital</th>
            </tr>
            <tr>
                <th>Kode</th>
                <th>Nama User</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Durasi</th>
                <th>Status</th>
                <th>Keterlambatan (hari)</th>
                <th>Denda</th>
                <th>Tanggal Cetak</th>
            </tr>
        </thead>
        <tbody>
        @forelse($loans as $loan)
            @php
                $status = $loan->status ?? 'dipinjam';
                $durationUnit = $loan->duration_unit ?? 'day';
                $durationValue = $loan->duration_value ?? $loan->duration_days;
                $durationText = $durationValue ? ($durationValue . ' ' . ($durationUnit === 'hour' ? 'jam' : 'hari')) : '-';

                $startAt = $loan->loan_start_at ? \Carbon\Carbon::parse($loan->loan_start_at) : null;
                $dueAt = $loan->due_at ? \Carbon\Carbon::parse($loan->due_at) : null;

                if (!$dueAt && $loan->loan_date && $loan->duration_days) {
                    $dueAt = \Carbon\Carbon::parse($loan->loan_date)->addDays((int) $loan->duration_days);
                }

                $loanDateText = $startAt
                    ? ($durationUnit === 'hour' ? $startAt->format('Y-m-d H:i') : $startAt->toDateString())
                    : ($loan->loan_date ?? '-');

                $dueDateText = $dueAt
                    ? ($durationUnit === 'hour' ? $dueAt->format('Y-m-d H:i') : $dueAt->toDateString())
                    : ($loan->return_date ?? '-');
            @endphp
            <tr>
                <td>{{ $loan->loan_code ?? '-' }}</td>
                <td>{{ optional($loan->user)->full_name ?? '-' }}</td>
                <td>{{ optional($loan->book)->title ?? '-' }}</td>
                <td>{{ $loanDateText }}</td>
                <td>{{ $dueDateText }}</td>
                <td>{{ $durationText }}</td>
                <td>{{ str_replace('_', ' ', ucfirst($status)) }}</td>
                <td>{{ $loan->display_late_days ?? 0 }}</td>
                <td>{{ $loan->display_fine_amount ?? 0 }}</td>
                <td>{{ now()->format('Y-m-d H:i:s') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10">Belum ada data peminjaman.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
