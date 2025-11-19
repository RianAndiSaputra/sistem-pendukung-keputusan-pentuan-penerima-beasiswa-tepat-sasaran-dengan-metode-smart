<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Seleksi Beasiswa - {{ $periode->nama_periode }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        .university { font-size: 14px; font-weight: bold; color: #1e40af; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f8fafc; font-weight: bold; }
        .table tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .status-lolos { background-color: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 10px; font-size: 10px; }
        .status-tidak { background-color: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 10px; font-size: 10px; }
        .summary { margin-bottom: 15px; padding: 10px; background-color: #f0f9ff; border-radius: 5px; }
        .summary-item { display: inline-block; margin-right: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="university">UNIVERSITAS MERCU BUANA YOGYAKARTA</div>
        <h1>LAPORAN HASIL SELEKSI BEASISWA</h1>
        <p>Periode: {{ $periode->nama_periode }}</p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d F Y') }} - {{ \Carbon\Carbon::parse($periode->tanggal_berakhir)->format('d F Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item"><strong>Total Peserta:</strong> {{ $hasilSeleksi->count() }}</div>
        <div class="summary-item"><strong>Peserta Lolos:</strong> {{ $hasilSeleksi->where('status', true)->count() }}</div>
        <div class="summary-item"><strong>Rata-rata Skor:</strong> {{ number_format($hasilSeleksi->avg('total_skor'), 2) }}</div>
        <div class="summary-item"><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">Rank</th>
                <th width="15%">NIM</th>
                <th width="25%">Nama Mahasiswa</th>
                <th width="20%">Program Studi</th>
                <th width="8%">IPK</th>
                <th width="12%">Total Skor</th>
                <th width="10%">Status</th>
                <th width="15%">Detail Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilSeleksi as $hasil)
            <tr>
                <td style="text-align: center;">{{ $hasil->ranking }}</td>
                <td>{{ $hasil->mahasiswa->nim }}</td>
                <td>{{ $hasil->mahasiswa->nama }}</td>
                <td>{{ $hasil->mahasiswa->prodi }}</td>
                <td style="text-align: center;">{{ $hasil->mahasiswa->ipk }}</td>
                <td style="text-align: center; font-weight: bold;">{{ number_format($hasil->total_skor, 2) }}</td>
                <td style="text-align: center;">
                    @if($hasil->status)
                        <span class="status-lolos">LOLOS</span>
                    @else
                        <span class="status-tidak">TIDAK</span>
                    @endif
                </td>
                <td style="font-size: 10px; text-align: center;">
                    IPK:{{ number_format($hasil->skor_ipk, 2) }} | 
                    PGH:{{ number_format($hasil->skor_penghasilan, 2) }}<br>
                    TGG:{{ number_format($hasil->skor_tanggungan, 2) }} | 
                    PRS:{{ number_format($hasil->skor_prestasi, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ auth('admin')->user()->name }} | Sistem Pendukung Keputusan Beasiswa UMBY</p>
        <p>Metode SMART - Universitas Mercu Buana Yogyakarta</p>
    </div>
</body>
</html>