<?php

namespace App\Exports;

use App\Models\PeriodeSeleksi;
use App\Models\HasilSeleksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $periode;
    protected $hasilSeleksi;

    public function __construct(PeriodeSeleksi $periode, $hasilSeleksi)
    {
        $this->periode = $periode;
        $this->hasilSeleksi = $hasilSeleksi;
    }

    public function collection()
    {
        return $this->hasilSeleksi;
    }

    public function headings(): array
    {
        return [
            'RANKING',
            'NIM',
            'NAMA MAHASISWA',
            'PROGRAM STUDI',
            'IPK',
            'SKOR IPK',
            'SKOR PENGHASILAN',
            'SKOR TANGGUNGAN',
            'SKOR PRESTASI',
            'TOTAL SKOR',
            'STATUS',
            'KETERANGAN'
        ];
    }

    public function map($hasil): array
    {
        return [
            $hasil->ranking,
            $hasil->mahasiswa->nim,
            $hasil->mahasiswa->nama,
            $hasil->mahasiswa->prodi,
            $hasil->mahasiswa->ipk,
            number_format($hasil->skor_ipk, 2),
            number_format($hasil->skor_penghasilan, 2),
            number_format($hasil->skor_tanggungan, 2),
            number_format($hasil->skor_prestasi, 2),
            number_format($hasil->total_skor, 2),
            $hasil->status ? 'LOLOS' : 'TIDAK LOLOS',
            $hasil->status ? 
                "Berhasil lolos seleksi beasiswa periode {$this->periode->nama_periode}" : 
                "Tidak memenuhi kuota penerima periode {$this->periode->nama_periode}"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Total rows
        $totalRows = $this->hasilSeleksi->count() + 1;
        
        // Style untuk header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '3490DC']
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ]
        ]);

        // Add border to all cells
        $sheet->getStyle("A1:L{$totalRows}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Center align for numeric columns
        $sheet->getStyle("A2:A{$totalRows}")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("E2:K{$totalRows}")->getAlignment()->setHorizontal('center');

        // Highlight rows yang lolos
        for ($row = 2; $row <= $totalRows; $row++) {
            $status = $sheet->getCell("K{$row}")->getValue();
            if ($status === 'LOLOS') {
                $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'C6F6D5']
                    ]
                ]);
            }
        }

        // Add summary row
        $summaryRow = $totalRows + 2;
        $sheet->setCellValue("A{$summaryRow}", "SUMMARY PERIODE: {$this->periode->nama_periode}");
        $sheet->getStyle("A{$summaryRow}")->getFont()->setBold(true);
        
        $summaryRow++;
        $sheet->setCellValue("A{$summaryRow}", "Total Peserta:");
        $sheet->setCellValue("B{$summaryRow}", $this->hasilSeleksi->count());
        
        $summaryRow++;
        $sheet->setCellValue("A{$summaryRow}", "Total Lolos:");
        $sheet->setCellValue("B{$summaryRow}", $this->hasilSeleksi->where('status', true)->count());
        
        $summaryRow++;
        $sheet->setCellValue("A{$summaryRow}", "Kuota Penerima:");
        $sheet->setCellValue("B{$summaryRow}", $this->periode->kuota_penerima);
        
        $summaryRow++;
        $sheet->setCellValue("A{$summaryRow}", "Tanggal Export:");
        $sheet->setCellValue("B{$summaryRow}", now()->format('d F Y H:i:s'));

        return [];
    }

    public function title(): string
    {
        return 'Hasil Seleksi ' . substr($this->periode->nama_periode, 0, 31);
    }
}