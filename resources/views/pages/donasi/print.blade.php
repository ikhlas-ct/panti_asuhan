<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Riwayat Donasi – {{ $donatur->nama }}</title>
    <style>
        /* ── RESET ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
        }

        /* ── SCREEN WRAPPER ── */
        .page-wrapper {
            max-width: 940px;
            margin: 0 auto;
            padding: 28px 36px 36px;
        }

        /* ── PRINT BUTTON (screen only) ── */
        .screen-only {
            text-align: center;
            margin-bottom: 18px;
        }
        .btn-print-now {
            display: inline-block;
            padding: 9px 32px;
            background: #000;
            color: #fff;
            border: none;
            font-family: Arial, sans-serif;
            font-size: 11pt;
            cursor: pointer;
            letter-spacing: .4px;
            border-radius: 4px;
            margin-right: 8px;
        }
        .btn-back {
            display: inline-block;
            padding: 9px 22px;
            background: #fff;
            color: #000;
            border: 1px solid #000;
            font-family: Arial, sans-serif;
            font-size: 11pt;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 6px;
        }
        .header-left { display: flex; align-items: center; gap: 14px; }
        .logo-box {
            width: 58px; height: 58px;
            border: 2px solid #000;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 8.5pt; font-weight: bold; flex-shrink: 0;
            overflow: hidden;
        }
        .logo-box img { width: 100%; height: 100%; object-fit: cover; }
        .org-info h1 { font-size: 13pt; font-weight: bold; letter-spacing: .4px; text-transform: uppercase; }
        .org-info p  { font-size: 9pt; line-height: 1.5; }
        .doc-label   { text-align: right; }
        .doc-label .badge-doc {
            display: inline-block;
            border: 2px solid #000;
            padding: 5px 18px;
            font-size: 12pt; font-weight: bold;
            letter-spacing: 1px; text-transform: uppercase;
        }
        .doc-label .sub { font-size: 9pt; margin-top: 4px; }

        /* ── META BAR ── */
        .meta-bar {
            display: flex; justify-content: space-between;
            font-size: 9pt;
            margin: 8px 0 4px;
            border-bottom: 1px solid #000;
            padding-bottom: 6px;
        }

        /* ── INFO DONATUR ── */
        .info-donatur-row {
            display: flex; gap: 30px;
            font-size: 9.5pt;
            margin: 6px 0 12px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 8px;
        }
        .info-donatur-row span strong { margin-right: 4px; }

        /* ── TABLE ── */
        table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        thead tr { background: #fff; }
        thead th {
            padding: 7px 8px; font-size: 9.5pt; font-weight: bold;
            text-align: center; border: 1px solid #000;
        }
        tbody tr { border-bottom: 1px solid #aaa; }
        tbody tr:nth-child(even) { background: #f5f5f5; }
        tbody td { padding: 6px 8px; font-size: 9.5pt; border-left: 1px solid #aaa; border-right: 1px solid #aaa; vertical-align: middle; }
        td.center { text-align: center; }
        td.right  { text-align: right; }

        /* ── TOTAL ROW ── */
        .total-row td {
            background: #e8e8e8 !important;
            font-weight: bold; font-size: 10pt;
            border: 1px solid #000; padding: 7px 8px;
        }

        /* ── EMPTY STATE ── */
        .empty-row td {
            text-align: center; padding: 20px;
            font-style: italic; color: #555;
            border: 1px solid #aaa;
        }

        /* ── SIGNATURE ── */
        .signature-section {
            display: flex; justify-content: space-between;
            margin-top: 28px; font-size: 9.5pt;
        }
        .sig-block { text-align: center; min-width: 200px; }
        .sig-block .place-date { margin-bottom: 48px; }
        .sig-block .role { margin-bottom: 4px; font-weight: bold; }
        .sig-line { border-top: 1px solid #000; margin: 0 auto; width: 160px; }
        .sig-block .name { margin-top: 4px; font-size: 9pt; }

        /* ── FOOTER ── */
        .doc-footer {
            margin-top: 24px; border-top: 1px solid #000;
            padding-top: 6px; text-align: center; font-size: 8pt; color: #333;
        }

        /* ── PRINT MEDIA ── */
        @media print {
            body { padding: 0; background: #fff; }
            .page-wrapper { padding: 0; max-width: 100%; margin: 0; }
            .screen-only { display: none; }
            tbody tr:nth-child(even) { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .total-row td { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { size: A4 landscape; margin: 14mm 12mm; }
        }
    </style>
</head>
<body>

    {{-- Tombol hanya tampil di layar --}}
    <div class="screen-only">
        <button class="btn-print-now" onclick="window.print()">🖨️ Cetak Dokumen</button>
        <a class="btn-back" href="{{ url()->previous() }}">← Kembali</a>
    </div>

    <div class="page-wrapper">

        {{-- ── HEADER ── --}}
        <div class="header">
            <div class="header-left">
                <div class="logo-box">
                    @if($setting && $setting->logo)
                        <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo">
                    @else
                        Logo
                    @endif
                </div>
                <div class="org-info">
                    <h1>{{ $setting->nama ?? 'Sistem Informasi Donasi Panti Asuhan' }}</h1>
                    <p>Dinas Sosial Kabupaten / Kota</p>
                    <p>{{ $setting->alamat ?? 'Jl. Contoh No. 1, Kota' }}</p>
                </div>
            </div>
            <div class="doc-label">
                <div class="badge-doc">Riwayat Donasi</div>
                <div class="sub">Laporan Donatur</div>
            </div>
        </div>

        {{-- ── META BAR ── --}}
        <div class="meta-bar">
            <span>Tanggal Cetak: <strong>{{ now()->translatedFormat('d F Y H:i') }}</strong></span>
            <span>
                Periode: <strong>{{ $periodeLabel }}</strong>
                &nbsp;|&nbsp;
                Status: <strong>{{ $statusLabel }}</strong>
            </span>
        </div>

        {{-- ── INFO DONATUR ── --}}
        <div class="info-donatur-row">
            <span><strong>Nama Donatur :</strong> {{ $donatur->nama }}</span>
            <span><strong>Jenis :</strong> {{ ucfirst($donatur->jenis_donatur) }}</span>
            @if($donatur->no_telp)
                <span><strong>No. Telp :</strong> {{ $donatur->no_telp }}</span>
            @endif
            @if($donatur->alamat)
                <span><strong>Alamat :</strong> {{ $donatur->alamat }}</span>
            @endif
        </div>

        {{-- ── TABLE ── --}}
        <table>
            <thead>
                <tr>
                    <th style="width:4%">No</th>
                    <th style="width:11%">Tanggal Donasi</th>
                    <th style="width:22%">Nama Panti Asuhan</th>
                    <th style="width:9%">Jenis Donasi</th>
                    <th style="width:10%">Metode</th>
                    <th style="width:30%">Nominal / Keterangan Barang</th>
                    <th style="width:10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donasis as $i => $donasi)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td class="center">{{ $donasi->tanggal_donasi?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td>{{ $donasi->pantiAsuhan->nama_panti ?? '-' }}</td>
                    <td class="center">{{ ucfirst($donasi->jenis_donasi) }}</td>
                    <td class="center">{{ ucfirst($donasi->metode) }}</td>
                    <td class="{{ $donasi->jenis_donasi === 'uang' ? 'right' : '' }}">
                        @if($donasi->jenis_donasi === 'uang')
                            Rp {{ number_format($donasi->nominal, 0, ',', '.') }}
                        @else
                            @if($donasi->barang->isNotEmpty())
                                {{ $donasi->barang->map(fn($b) => $b->nama_barang . ' (' . $b->jumlah_barang . ' ' . $b->satuan_barang . ')')->implode(', ') }}
                            @else
                                {{ $donasi->deskripsi_barang ?? '-' }}
                            @endif
                        @endif
                    </td>
                    <td class="center">{{ ucfirst($donasi->status) }}</td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="7">Tidak ada data donasi pada periode yang dipilih.</td>
                </tr>
                @endforelse

                {{-- Total row --}}
                @if($donasis->isNotEmpty())
                <tr class="total-row">
                    <td colspan="5" style="text-align:right; border-right:1px solid #000;">
                        Total Donasi Uang Diterima :
                    </td>
                    <td class="right">Rp {{ number_format($totalUang, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- ── SIGNATURE ── --}}
        <div class="signature-section">
            <div class="sig-block">
                <div class="place-date">{{ now()->translatedFormat('d F Y') }}</div>
                <div class="role">Mengetahui,</div>
                <br>
                <div class="sig-line"></div>
                <div class="name">( {{ $pegawaiNama ?? 'Nama Pegawai' }} )</div>
            </div>
            <div class="sig-block">
                <div class="place-date">{{ now()->translatedFormat('d F Y') }}</div>
                <div class="role">Donatur,</div>
                <br>
                <div class="sig-line"></div>
                <div class="name">( {{ $donatur->nama }} )</div>
            </div>
        </div>

        {{-- ── FOOTER ── --}}
        <div class="doc-footer">
            {{ $setting->nama ?? 'Sistem Informasi Donasi Panti Asuhan' }} &mdash; Dinas Sosial Kabupaten / Kota
        </div>

    </div>{{-- end page-wrapper --}}

      <script>
    window.addEventListener('load', function () {
      // Tunda sedikit agar gambar logo sempat dimuat sebelum dialog print muncul
      setTimeout(function () {
        window.print();
      }, 600);
    });
  </script>

</body>
</html>
