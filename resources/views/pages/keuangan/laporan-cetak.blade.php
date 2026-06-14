<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <title>Laporan Keuangan – {{ $panti->nama_panti }}</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }

    body {
      background:#ccc;
      display:flex; justify-content:center;
      padding:30px 0 60px;
      font-family:'Times New Roman', Times, serif;
      font-size:11pt; color:#000;
    }

    .page {
      width:210mm; min-height:297mm;
      background:#fff; padding:15mm 18mm;
      box-shadow:0 4px 20px rgba(0,0,0,.3);
    }

    /* ── HEADER ── */
    .header {
      display:flex; align-items:center; gap:14px;
      padding-bottom:10px; border-bottom:2.5px solid #000; margin-bottom:6px;
    }

    .logo-box {
      width:58px; height:58px;
      display:flex; align-items:center; justify-content:center;
      font-size:8pt; flex-shrink:0; overflow:hidden;
    }
    .logo-box img { width:100%; height:100%; object-fit:contain; }

    .org-info  { flex:1; }
    .org-name  { font-size:15pt; font-weight:bold; line-height:1.2; }
    .org-slogan{ font-size:9pt; font-style:italic; margin-top:1px; }
    .org-sub   { font-size:9pt; margin-top:2px; }
    .org-addr  { font-size:8.5pt; margin-top:2px; color:#333; }

    .header-right { text-align:right; flex-shrink:0; }
    .doc-title {
      font-size:13pt; font-weight:bold;
      border:2px solid #000; padding:5px 14px; display:inline-block;
    }
    .doc-period { font-size:8.5pt; margin-top:5px; }

    /* ── META ── */
    .meta {
      display:flex; justify-content:space-between;
      font-size:9pt; padding:5px 0 7px; border-bottom:1px solid #000;
    }

    /* ── SUMMARY ── */
    .summary { display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px; margin:10px 0 12px; }
    .card    { border:1.5px solid #000; padding:8px 10px; text-align:center; }
    .card-label  { font-size:8pt; text-transform:uppercase; margin-bottom:3px; }
    .card-amount { font-size:13.5pt; font-weight:bold; }

    /* ── TABLE ── */
    table { width:100%; border-collapse:collapse; font-size:9.5pt; margin-top:4px; }

    thead th {
      background:#000; color:#fff; padding:6px 7px;
      font-size:8.5pt; text-align:left; text-transform:uppercase;
    }
    thead th.center { text-align:center; width:28px; }
    thead th.right  { text-align:right; }

    tbody td { padding:5px 7px; border-bottom:0.5px solid #aaa; vertical-align:middle; }
    tbody tr:nth-child(even) td { background:#f5f5f5; }

    td.center { text-align:center; }
    td.right  { text-align:right; font-variant-numeric:tabular-nums; }

    .jenis-box {
      display:inline-block; border:1px solid #000;
      padding:1px 8px; font-size:8pt;
    }

    /* ── TOTALS ── */
    .totals-table { width:100%; border-collapse:collapse; margin-top:0; }
    .totals-table td { padding:4px 7px; font-size:9.5pt; border:none; }
    .totals-table .label { text-align:right; padding-right:12px; }
    .totals-table .value { text-align:right; font-weight:bold; width:130px; }

    .row-saldo td {
      background:#000; color:#fff;
      font-size:11pt; font-weight:bold; padding:6px 7px;
    }
    .divider { border-top:1.5px solid #000; margin:2px 0; }

    /* ── SIGNATURE ── */
    .signature { display:flex; justify-content:space-between; margin-top:22px; font-size:9.5pt; }
    .sig-block { text-align:center; min-width:140px; }
    .sig-block .date  { margin-bottom:44px; }
    .sig-block .line  { border-top:1px solid #000; padding-top:3px; font-weight:bold; }
    .sig-block .title { font-size:8.5pt; }

    /* ── FOOTER ── */
    .footer { margin-top:16px; padding-top:6px; border-top:1px solid #000; text-align:center; font-size:8pt; }

    /* ── PRINT ── */
    @media print {
      body { background:none; padding:0; }
      .page { box-shadow:none; }
    }
    @page { size:A4; margin:0; }
  </style>
</head>
<body>

  <div class="page">

    <!-- HEADER — data dari WebsiteSetting -->
    <div class="header">

      <div class="logo-box">
        @if($setting && $setting->logo)
          <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo">
        @else
          <span>LOGO</span>
        @endif
      </div>

      <div class="org-info">
        {{-- Nama institusi dari website_settings --}}
        <div class="org-name">{{ $setting->nama ?? $panti->nama_panti }}</div>

        @if($setting && $setting->slogan)
          <div class="org-slogan">{{ $setting->slogan }}</div>
        @endif

        <div class="org-sub">Dinas Sosial Kabupaten / Kota</div>

        <div class="org-addr">
          {{ $setting->alamat ?? $panti->alamat ?? '-' }}
          @if($setting && $setting->nomor_telepon)
            &nbsp;·&nbsp; Telp. {{ $setting->nomor_telepon }}
          @endif
          @if($setting && $setting->email)
            &nbsp;·&nbsp; {{ $setting->email }}
          @endif
        </div>
      </div>

      <div class="header-right">
        <div class="doc-title">LAPORAN KEUANGAN</div>
        <div class="doc-period">
          @if($tipe === 'harian')    Laporan Harian
          @elseif($tipe === 'bulanan') Periode Bulanan
          @else                        Periode Tahunan
          @endif
        </div>
      </div>

    </div>

    <!-- META -->
    <div class="meta">
      <span>
        Panti Asuhan: <b>{{ $panti->nama_panti }}</b>
        &nbsp;|&nbsp;
        Periode: <b>{{ $labelPeriode }}</b>
      </span>
      <span>Tanggal Cetak: <b>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</b></span>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="summary">
      <div class="card">
        <div class="card-label">Total Pemasukan</div>
        <div class="card-amount">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
      </div>
      <div class="card">
        <div class="card-label">Total Pengeluaran</div>
        <div class="card-amount">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
      </div>
      <div class="card">
        <div class="card-label">Saldo</div>
        <div class="card-amount">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
      </div>
    </div>

    <!-- TRANSACTION TABLE -->
    <table>
      <thead>
        <tr>
          <th class="center">No</th>
          <th>Tanggal</th>
          <th>Kategori</th>
          <th>Keterangan</th>
          <th>Jenis</th>
          <th class="right">Nominal (Rp)</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transaksi as $i => $item)
          <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
            <td>{{ $item->kategori ?? '-' }}</td>
            <td>{{ $item->keterangan ?? '-' }}</td>
            <td><span class="jenis-box">{{ ucfirst($item->jenis) }}</span></td>
            <td class="right">{{ number_format($item->nominal, 0, ',', '.') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align:center; padding:20px; color:#666; font-style:italic;">
              Tidak ada transaksi pada periode ini.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <!-- TOTALS -->
    <div class="divider"></div>
    <table class="totals-table">
      <tr>
        <td class="label">Total Pemasukan :</td>
        <td class="value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td class="label">Total Pengeluaran :</td>
        <td class="value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
      </tr>
      <tr class="row-saldo">
        <td class="label" style="text-align:right; padding-right:12px;">SALDO :</td>
        <td class="value" style="text-align:right;">Rp {{ number_format($saldo, 0, ',', '.') }}</td>
      </tr>
    </table>

    <!-- SIGNATURE -->
    <div class="signature">
      <div class="sig-block">
        <div>Mengetahui,</div>
        <div class="date">
          {{ $panti->kelurahan ?? ($setting->alamat ? '' : 'Kota') }},
          {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
        <div class="line">{{ $kepaladinsos ?? '.......................................' }}</div>
        <div class="title">Kepala Dinas Sosial</div>
      </div>
      <div class="sig-block">
        <div>Dibuat oleh,</div>
        <div class="date">
          {{ $panti->kelurahan ?? '' }},
          {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
        <div class="line">{{ $pengurusNama ?? '.......................................' }}</div>
        <div class="title">Pengurus Panti</div>
      </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
      {{ $setting->nama ?? 'Sistem Informasi Donasi Panti Asuhan' }}
      – Dinas Sosial Kabupaten / Kota
    </div>

  </div>

  {{-- ── Auto-print saat halaman selesai render ── --}}
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
