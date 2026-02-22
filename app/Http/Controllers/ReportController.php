<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function loans()
    {
        $this->ensureStaffAccess();

        $loans = Loan::with(['user', 'book'])
            ->latest('loan_date')
            ->get();

        return view('admin.reports.loans', compact('loans'));
    }

    public function printLoans()
    {
        $this->ensureStaffAccess();

        $loans = Loan::with(['user', 'book'])
            ->latest('loan_date')
            ->get();

        return view('admin.reports.print_loans', compact('loans'));
    }

    public function exportLoansExcel()
    {
        $this->ensureStaffAccess();

        $loans = Loan::with(['user', 'book'])
            ->latest('loan_date')
            ->get();

        $fileName = 'laporan-peminjaman-' . now()->format('Y-m-d-His') . '.xlsx';

        $rows = [[
            'Kode',
            'Nama User',
            'Judul Buku',
            'Tanggal Pinjam',
            'Jatuh Tempo',
            'Durasi',
            'Status',
            'Keterlambatan (hari)',
            'Denda',
            'Tanggal Cetak',
        ]];

        foreach ($loans as $loan) {
            $status = $loan->status ?? 'dipinjam';
            $durationUnit = $loan->duration_unit ?? 'day';
            $durationValue = $loan->duration_value ?? $loan->duration_days;
            $durationText = $durationValue
                ? ($durationValue . ' ' . ($durationUnit === 'hour' ? 'jam' : 'hari'))
                : '-';

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

            $rows[] = [
                $loan->loan_code ?? '-',
                optional($loan->user)->full_name ?? '-',
                optional($loan->book)->title ?? '-',
                $loanDateText,
                $dueDateText,
                $durationText,
                str_replace('_', ' ', ucfirst($status)),
                (string) ($loan->display_late_days ?? 0),
                (string) ($loan->display_fine_amount ?? 0),
                now()->format('Y-m-d H:i:s'),
            ];
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'xlsx_');
        if ($tmpPath === false) {
            abort(500, 'Gagal membuat file export.');
        }

        $zip = new \ZipArchive();
        if ($zip->open($tmpPath, \ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Gagal membuat file excel.');
        }

        $sheetData = '';
        foreach ($rows as $rowIndex => $row) {
            $r = $rowIndex + 1;
            $sheetData .= '<row r="' . $r . '">';
            foreach ($row as $colIndex => $value) {
                $colRef = $this->excelColumnName($colIndex + 1) . $r;
                $safeValue = htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
                $sheetData .= '<c r="' . $colRef . '" t="inlineStr"><is><t>' . $safeValue . '</t></is></c>';
            }
            $sheetData .= '</row>';
        }

        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
</Types>');

        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>');

        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Laporan Peminjaman" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>');

        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
</Relationships>');

        $zip->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <sheetData>' . $sheetData . '</sheetData>
</worksheet>');

        $zip->close();

        return response()->download(
            $tmpPath,
            $fileName,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    private function excelColumnName(int $columnIndex): string
    {
        $name = '';
        while ($columnIndex > 0) {
            $mod = ($columnIndex - 1) % 26;
            $name = chr(65 + $mod) . $name;
            $columnIndex = intdiv($columnIndex - 1, 26);
        }

        return $name;
    }

    private function ensureStaffAccess(): void
    {
        abort_unless(
            Auth::check() && in_array(Auth::user()->role, ['admin', 'petugas'], true),
            403
        );
    }
}
