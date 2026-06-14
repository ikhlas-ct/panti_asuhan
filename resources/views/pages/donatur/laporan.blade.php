<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Per Donatur – {{ $setting?->nama ?? 'Dinas Sosial' }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&family=Source+Serif+4:wght@600;700&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --txt-main:   #2a2a2a;
      --txt-sub:    #555555;
      --txt-light:  #888888;
      --border:     #cccccc;
      --border-mid: #aaaaaa;
      --bg-row:     #f7f7f7;
      --bg-header:  #eeeeee;
      --bg-page:    #ffffff;
      --font-body:  'Source Sans 3', sans-serif;
      --font-head:  'Source Serif 4', serif;
    }

    body {
      font-family: var(--font-body);
      color: var(--txt-main);
      background: #e0e0e0;
      padding: 28px;
    }

    .page {
      background: var(--bg-page);
      width: 297mm;
      margin: 0 auto;
      box-shadow: 0 2px 16px rgba(0,0,0,.12);
      border: 1px solid var(--border);
    }

    /* HEADER */
    .header {
      padding: 20px 28px 16px;
      display: flex; align-items: center;
      justify-content: space-between;
      border-bottom: 2px solid var(--border-mid);
    }
    .header-left { display: flex; align-items: center; gap: 16px; }
    .logo-box {
      width: 80px; height: 80px;
      display: flex; align-items: center; justify-content: center;
      overflow: hidden; flex-shrink: 0;
    }
    .logo-box img { width: 100%; height: 100%; object-fit: contain; }
    .logo-box .logo-placeholder {
      font-size: 8pt; color: var(--txt-sub); font-weight: 700; text-align: center; padding: 4px;
    }
    .header-title h1 {
      font-family: var(--font-head); font-size: 15pt;
      color: var(--txt-main); font-weight: 700; line-height: 1.2;
    }
    .header-title .instansi { font-size: 9pt; color: var(--txt-sub); margin-top: 2px; }
    .header-title .alamat   { font-size: 8pt; color: var(--txt-light); }

    .header-badge {
 border-radius: 4px;
      padding: 9px 18px; text-align: center;
    }
    .header-badge h2 {
      font-family: var(--font-head); font-size: 12pt;
      color: var(--txt-main); font-weight: 700;
    }
    .header-badge p { font-size: 8pt; color: var(--txt-light); margin-top: 2px; }

    /* INFO BAR */
    .info-bar {
      background: var(--bg-row); border-bottom: 1px solid var(--border);
      padding: 6px 28px; display: flex;
      justify-content: space-between; align-items: center;
      font-size: 9pt; color: var(--txt-sub);
    }
    .info-bar b { color: var(--txt-main); }

    /* CARDS */
    .cards {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 14px; padding: 14px 28px;
      border-bottom: 1px solid var(--border);
    }
    .card {
      border: 1px solid var(--border); border-radius: 4px;
      padding: 12px 18px; text-align: center;
    }
    .card .label {
      font-size: 8pt; font-weight: 700; letter-spacing: .5px;
      text-transform: uppercase; color: var(--txt-light);
    }
    .card .value {
      font-family: var(--font-head); font-size: 19pt;
      font-weight: 700; color: var(--txt-main);
      margin-top: 3px; line-height: 1.1;
    }

    /* TABLE */
    .table-wrap { padding: 0 28px 14px; }
    table { width: 100%; border-collapse: collapse; font-size: 9.5pt; }

    thead tr { background: var(--bg-header); }
    thead th {
      padding: 9px 8px; font-weight: 700; font-size: 8.5pt;
      color: var(--txt-main); text-align: center;
      border: 1px solid var(--border); letter-spacing: .2px;
    }
    thead th:nth-child(2), thead th:nth-child(5) { text-align: left; }

    tbody td {
      padding: 7px 8px; border: 1px solid var(--border);
      vertical-align: middle; font-size: 9pt; color: var(--txt-main);
    }
    tbody tr:nth-child(even) td { background: var(--bg-row); }
    tbody td.center { text-align: center; }
    tbody td.name   { font-weight: 600; }
    tbody td.email  { font-size: 8.5pt; color: var(--txt-sub); }

    .tbl-badge {
      display: inline-block; padding: 2px 8px; border-radius: 20px;
      font-size: 7.5pt; font-weight: 700;
    }
    .tbl-badge.aktif    { background: #dcfce7; color: #15803d; }
    .tbl-badge.nonaktif { background: #fee2e2; color: #b91c1c; }

    /* TFOOT */
    tfoot td {
      padding: 7px 8px; border: 1px solid var(--border);
      font-size: 9pt; background: var(--bg-row);
    }
    tfoot .sum-label { text-align: right; font-weight: 700; color: var(--txt-sub); }
    tfoot .sum-val   { text-align: center; font-weight: 700; color: var(--txt-main); }

    /* KETERANGAN */
    .keterangan {
      margin: 4px 28px 18px; border: 1px solid var(--border);
      border-radius: 3px; padding: 8px 14px;
      font-size: 8.5pt; color: var(--txt-sub);
      line-height: 1.7; background: var(--bg-row);
    }

    /* TTD */
    .ttd-section {
      display: flex; justify-content: flex-end;
      padding: 6px 28px 22px;
    }
    .ttd-box { width: 220px; }
    .ttd-top  { font-size: 9pt; color: var(--txt-sub); margin-bottom: 2px; }
    .ttd-top b { color: var(--txt-main); }
    .ttd-pos  { font-weight: 600; font-size: 9pt; color: var(--txt-main); margin-bottom: 52px; }
    .ttd-line { border-top: 1px solid var(--txt-main); margin-bottom: 4px; }
    .ttd-name { font-size: 9pt; font-weight: 700; color: var(--txt-main); }
    .ttd-jabatan { font-size: 8pt; color: var(--txt-sub); margin-top: 1px; }

    /* FOOTER */
    .footer-bar {
      border-top: 1px solid var(--border); padding: 8px 28px;
      text-align: center; font-size: 8pt; color: var(--txt-light);
      letter-spacing: .3px; background: var(--bg-row);
    }

    /* empty state */
    .empty-table td {
      text-align: center; padding: 24px;
      color: var(--txt-light); font-style: italic; font-size: 9pt;
    }

    @media print {
      @page { size: A4 landscape; margin: 8mm 10mm; }
      body  { background: none; padding: 0; }
      .page { box-shadow: none; width: 100%; border: none; }
    }
  </style>
</head>
<body>

  <div class="page">

    {{-- ── HEADER ── --}}
    <header class="header">
      <div class="header-left">

        {{-- Logo dari WebsiteSetting --}}
        <div class="logo-box">
          @if($setting?->logo && Storage::disk('public')->exists($setting->logo))
            <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo">
          @else
            <span class="logo-placeholder">Logo</span>
          @endif
        </div>

        <div class="header-title">
          <h1>LAPORAN PER DONATUR</h1>
          <p class="instansi">{{ $setting?->nama ?? 'Dinas Sosial Kabupaten / Kota' }}</p>
          <p class="alamat">{{ $setting?->alamat ?? 'Alamat instansi belum diatur' }}</p>
        </div>
      </div>

      <div class="header-badge">
        <h2>LAPORAN DONATUR</h2>
        <p>Data Pendaftaran Donatur</p>
      </div>
    </header>

    {{-- ── INFO BAR ── --}}
    <div class="info-bar">
      <span>
        Donatur: <b>Semua Donatur</b> &nbsp;|&nbsp;
        Periode: <b>{{ $subLabel }}</b> &nbsp;|&nbsp;
        Jenis: <b>Semua Jenis</b>
      </span>
      <span>Tanggal Cetak: <b>{{ $tanggalCetak }}</b></span>
    </div>

    {{-- ── CARDS ── --}}
    <div class="cards">
      <div class="card">
        <div class="label">Total Donatur Terdaftar</div>
        <div class="value">{{ $totalSemua }} Donatur</div>
      </div>
      <div class="card">
        <div class="label">Donatur Aktif</div>
        <div class="value">{{ $totalAktif }} Donatur</div>
      </div>
    </div>

    {{-- ── TABLE ── --}}
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:34px;">No</th>
            <th>Nama Donatur</th>
            <th style="width:100px;">Jenis Donatur</th>
            <th style="width:115px;">No. Telepon</th>
            <th>Email / Username</th>
            <th style="width:105px;">Tgl. Buat Akun</th>
            <th style="width:75px;">Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($donaturs as $i => $d)
          <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td class="name">{{ $d->nama }}</td>
            <td class="center">{{ ucfirst($d->jenis_donatur) }}</td>
            <td class="center">{{ $d->no_telp ?? '-' }}</td>
            <td class="email">
              @if($d->user)
                {{ $d->user->email }}
                <span style="font-size:7.5pt;color:#bbb;"> / {{ $d->user->username }}</span>
              @else
                <span style="color:#bbb;">—</span>
              @endif
            </td>
            <td class="center">
              {{ $d->created_at ? $d->created_at->translatedFormat('d M Y') : '-' }}
            </td>
            <td class="center">
              <span class="tbl-badge {{ $d->status }}">
                {{ $d->status === 'aktif' ? 'Aktif' : 'Non-aktif' }}
              </span>
            </td>
          </tr>
          @empty
          <tr class="empty-table">
            <td colspan="7">Tidak ada data donatur untuk periode ini.</td>
          </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5"></td>
            <td class="sum-label">Total Donatur Ditampilkan :</td>
            <td class="sum-val">{{ $totalSemua }} Donatur</td>
          </tr>
        </tfoot>
      </table>
    </div>

    {{-- ── KETERANGAN ── --}}
    <div class="keterangan">
      <b>Keterangan Jenis Donatur:</b>&nbsp;&nbsp;
      Perorangan = Individu / perseorangan &nbsp;|&nbsp;
      Organisasi = LSM / Yayasan / Komunitas &nbsp;|&nbsp;
      Perusahaan = Badan usaha swasta &nbsp;|&nbsp;
      Pemerintah = Instansi pemerintah
    </div>

    {{-- ── TANDA TANGAN (dari data Pegawai admin dinsos) ── --}}
    <div class="ttd-section">
      <div class="ttd-box">
        <div class="ttd-top">
          {{ $setting?->nama ? Str::words($setting->nama, 2, '') : 'Kota' }},
          <b>{{ $tanggalCetak }}</b>
        </div>
        <div class="ttd-pos">
          {{ $pegawai?->posisi ?? 'Kepala Dinas Sosial' }},
        </div>
        <div class="ttd-line"></div>
        <div class="ttd-name">{{ $pegawai?->nama ?? '-' }}</div>
        <div class="ttd-jabatan">{{ $pegawai?->posisi ?? '' }}</div>
      </div>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer-bar">
      Sistem Informasi Donasi Panti Asuhan – {{ $setting?->nama ?? 'Dinas Sosial Kabupaten / Kota' }}
    </div>

  </div>

  {{-- Auto-trigger print setelah halaman & font selesai dimuat --}}
  <script>
    window.addEventListener('load', function () {
      // Timeout singkat agar browser selesai render font & gambar
      setTimeout(function () { window.print(); }, 600);
    });
  </script>

</body>
</html>
