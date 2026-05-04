<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan – {{ $panti->nama_panti }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
        }

        /* ── Wrapper cetak ── */
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 20mm 25mm 25mm;
        }

        /* ── KOP SURAT ── */
        .kop {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .kop-logo {
            width: 70px;
            height: 70px;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 9pt;
            color: #999;
        }
        .kop-logo img { width: 100%; height: 100%; object-fit: contain; }
        .kop-text { flex: 1; text-align: center; }
        .kop-instansi { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; }
        .kop-alamat   { font-size: 10pt; margin-top: 3px; }

        /* ── JUDUL LAPORAN ── */
        .judul-wrap { text-align: center; margin: 18px 0 6px; }
        .judul-wrap h2 { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: .3px; }
        .judul-wrap .sub-judul { font-size: 11pt; margin-top: 2px; }

        /* ── INFO PANTI ── */
        .info-panti {
            border: 1px solid #000;
            border-radius: 4px;
            padding: 8px 12px;
            margin: 14px 0;
            font-size: 10.5pt;
            background: #f9f9f9;
        }
        .info-panti table { width: 100%; border-collapse: collapse; }
        .info-panti td { padding: 2px 6px 2px 0; vertical-align: top; }
        .info-panti td:first-child { width: 120px; font-weight: bold; white-space: nowrap; }
        .info-panti td:nth-child(2) { width: 8px; }

        /* ── TABEL DATA ── */
        .tabel-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            font-size: 10.5pt;
        }
        .tabel-data thead tr th {
            background: #1a1a1a;
            color: #fff;
            padding: 7px 8px;
            text-align: center;
            font-size: 10pt;
            border: 1px solid #000;
        }
        .tabel-data tbody tr td {
            padding: 5px 8px;
            border: 1px solid #555;
            vertical-align: top;
        }
        .tabel-data tbody tr:nth-child(even) td { background: #f5f5f5; }
        .tabel-data tbody tr:hover td { background: #eef; }

        .td-no        { text-align: center; width: 36px; }
        .td-tanggal   { text-align: center; width: 90px; white-space: nowrap; }
        .td-jenis     { text-align: center; width: 90px; }
        .td-kategori  { width: 110px; }
        .td-nominal   { text-align: right; width: 130px; white-space: nowrap; }
        .td-keterangan { }

        .jenis-masuk  { color: #15803d; font-weight: 700; }
        .jenis-keluar { color: #dc2626; font-weight: 700; }

        /* ── RINGKASAN ── */
        .ringkasan {
            margin-top: 14px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .ringkasan table {
            border-collapse: collapse;
            font-size: 10.5pt;
            min-width: 280px;
        }
        .ringkasan td {
            padding: 4px 10px;
            border: 1px solid #555;
        }
        .ringkasan .r-label { font-weight: bold; background: #f0f0f0; }
        .ringkasan .r-val   { text-align: right; white-space: nowrap; }
        .ringkasan .r-saldo {
            font-weight: bold; font-size: 11pt;
            background: #1a1a1a; color: #fff;
        }
        .ringkasan .r-saldo-val {
            font-weight: bold; font-size: 11pt; text-align: right;
            background: #1a1a1a; color: #fff; white-space: nowrap;
        }

        /* ── TANDA TANGAN ── */
        .ttd-wrap {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 10.5pt;
        }
        .ttd-box { text-align: center; }
        .ttd-box .ttd-label { margin-bottom: 60px; }
        .ttd-box .ttd-name  { font-weight: bold; border-top: 1px solid #000; padding-top: 4px; min-width: 160px; }

        /* ── FOOTER ── */
        .footer-cetak {
            margin-top: 30px;
            font-size: 9pt;
            color: #666;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 6px;
        }

        /* ── PRINT ── */
        @media print {
            body { background: #fff; }
            .page { width: 100%; padding: 12mm 15mm 20mm 20mm; margin: 0; }
            .no-print { display: none !important; }
        }

        /* ── TOMBOL (layar saja) ── */
        .toolbar {
            background: #1e293b;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .toolbar a, .toolbar button {
            color: #fff; font-family: sans-serif; font-size: .85rem;
            background: transparent; border: 1px solid #94a3b8;
            border-radius: 8px; padding: 6px 14px; cursor: pointer;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
        }
        .toolbar button.print-btn {
            background: #1a73e8; border-color: #1a73e8; font-weight: 600;
        }
        .toolbar button.print-btn:hover { background: #1558b0; }
        .toolbar span { color: #94a3b8; font-family: sans-serif; font-size: .83rem; }
    </style>
</head>
<body>

{{-- ── TOOLBAR (tidak ikut cetak) ── --}}
<div class="toolbar no-print">
    <a href="javascript:history.back()">← Kembali</a>
    <button class="print-btn" onclick="window.print()">🖨 Cetak / Simpan PDF</button>
    <span>
        Laporan: <strong style="color:#fff;">{{ $panti->nama_panti }}</strong>
        &nbsp;|&nbsp;
        Periode:
        <strong style="color:#fff;">
            @if($bulan && $tahun)
                {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
            @elseif($tahun)
                Tahun {{ $tahun }}
            @else
                Semua Periode
            @endif
        </strong>
    </span>
</div>

<div class="page">

    {{-- ── KOP ── --}}
    <div class="kop">
        <div class="kop-logo">
            @if(!empty($settings?->logo))
                <img src="{{ asset($settings->logo) }}" alt="Logo">
            @else
                Logo
            @endif
        </div>
        <div class="kop-text">
            <div class="kop-instansi">{{ $settings->nama_instansi ?? 'Dinas Sosial Kota Padang' }}</div>
            <div class="kop-alamat">{{ $settings->alamat ?? 'Jl. Rasuna Said No.X, Padang, Sumatera Barat' }}</div>
            @if(!empty($settings?->no_telp))
            <div class="kop-alamat">Telp. {{ $settings->no_telp }}</div>
            @endif
        </div>
    </div>

    {{-- ── JUDUL ── --}}
    <div class="judul-wrap">
        <h2>Laporan Keuangan Panti Asuhan</h2>
        <div class="sub-judul">
            Periode:
            @if($bulan && $tahun)
                {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
            @elseif($tahun)
                Tahun {{ $tahun }}
            @else
                Semua Periode
            @endif
        </div>
    </div>

    {{-- ── INFO PANTI ── --}}
    <div class="info-panti">
        <table>
            <tr>
                <td>Nama Panti</td>
                <td>:</td>
                <td><strong>{{ $panti->nama_panti }}</strong></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $panti->alamat }}{{ $panti->kelurahan ? ', ' . $panti->kelurahan : '' }}{{ $panti->kecamatan ? ', ' . $panti->kecamatan : '' }}</td>
            </tr>
            @if($panti->no_telp)
            <tr>
                <td>No. Telepon</td>
                <td>:</td>
                <td>{{ $panti->no_telp }}</td>
            </tr>
            @endif
            @if($panti->nama_kontak)
            <tr>
                <td>Kontak</td>
                <td>:</td>
                <td>{{ $panti->nama_kontak }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- ── TABEL TRANSAKSI ── --}}
    <table class="tabel-data">
        <thead>
            <tr>
                <th class="td-no">NO</th>
                <th class="td-tanggal">Tanggal</th>
                <th class="td-jenis">Jenis</th>
                <th class="td-kategori">Kategori</th>
                <th class="td-nominal">Nominal (Rp)</th>
                <th class="td-keterangan">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $i => $t)
            <tr>
                <td class="td-no">{{ $i + 1 }}</td>
                <td class="td-tanggal">{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y') }}</td>
                <td class="td-jenis">
                    <span class="{{ $t->jenis === 'pemasukan' ? 'jenis-masuk' : 'jenis-keluar' }}">
                        {{ ucfirst($t->jenis) }}
                    </span>
                </td>
                <td class="td-kategori">{{ $t->kategori ?? '-' }}</td>
                <td class="td-nominal">
                    <span class="{{ $t->jenis === 'pemasukan' ? 'jenis-masuk' : 'jenis-keluar' }}">
                        {{ $t->jenis === 'pengeluaran' ? '(' : '' }}{{ number_format($t->nominal, 0, ',', '.') }}{{ $t->jenis === 'pengeluaran' ? ')' : '' }}
                    </span>
                </td>
                <td class="td-keterangan">{{ $t->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:16px; color:#666;">
                    Tidak ada data transaksi pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── RINGKASAN ── --}}
    <div class="ringkasan">
        <table>
            <tr>
                <td class="r-label">Total Pemasukan</td>
                <td class="r-val jenis-masuk">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="r-label">Total Pengeluaran</td>
                <td class="r-val jenis-keluar">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="r-saldo">Saldo Akhir</td>
                <td class="r-saldo-val">Rp {{ number_format($saldo, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- ── TANDA TANGAN ── --}}
    <div class="ttd-wrap">
        <div class="ttd-box">
            <div class="ttd-label">Mengetahui,<br>Pengurus Panti Asuhan</div>
            <div class="ttd-name">{{ $panti->nama_kontak ?? '..............................' }}</div>
        </div>
        <div class="ttd-box">
            <div class="ttd-label">
                Padang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Kepala Dinas Sosial
            </div>
            <div class="ttd-name">{{ $kepalaDinsos?->nama ?? '..............................' }}</div>
            @if($kepalaDinsos?->posisi)
            <div style="font-size:9.5pt; font-weight:normal; margin-top:2px;">{{ $kepalaDinsos->posisi }}</div>
            @endif
        </div>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer-cetak no-print">
        Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}
    </div>

</div>
<script>
    // Auto print saat halaman dibuka
    window.addEventListener('load', function () {
        setTimeout(function () {
            window.print();
        }, 600); // delay sedikit agar halaman render sempurna
    });
</script>
</body>
</html>
