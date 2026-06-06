@extends('layouts.user.user')

@section('title', 'Pusat Laporan')

@section('styles')
<style>
    /* ── Page Header ── */
    .ph-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        padding: 16px 20px; display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
        margin-bottom: 1.25rem; position: relative; overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; border-radius: 14px 0 0 14px; background: #7c3aed;
    }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon {
        width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
        background: #f5f3ff; color: #7c3aed;
    }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px; flex-wrap: wrap;
        margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #7c3aed; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ── Laporan Cards ── */
    .laporan-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
        transition: transform .15s, box-shadow .15s;
        height: 100%;
    }
    .laporan-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,.1);
    }
    .laporan-card-header {
        padding: 28px 24px 20px;
        position: relative;
        overflow: hidden;
    }
    .laporan-card-header::after {
        content: '';
        position: absolute;
        right: -20px; top: -20px;
        width: 90px; height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,.12);
    }
    .laporan-card-header.cyan   { background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%); }
    .laporan-card-header.violet { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); }
    .laporan-card-header.emerald{ background: linear-gradient(135deg, #059669 0%, #047857 100%); }

    .laporan-icon {
        width: 52px; height: 52px; border-radius: 14px;
        background: rgba(255,255,255,.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; color: #fff; margin-bottom: 14px;
        backdrop-filter: blur(4px);
    }
    .laporan-title {
        font-size: 1.1rem; font-weight: 800; color: #fff;
        margin: 0 0 4px;
    }
    .laporan-subtitle {
        font-size: .78rem; color: rgba(255,255,255,.8);
        margin: 0;
    }

    .laporan-card-body {
        padding: 20px 24px;
    }
    .laporan-desc {
        font-size: .83rem; color: #64748b; line-height: 1.6; margin-bottom: 16px;
    }
    .laporan-feature {
        display: flex; align-items: center; gap: 8px;
        font-size: .78rem; color: #475569; padding: 4px 0;
    }
    .laporan-feature i { width: 16px; text-align: center; color: #94a3b8; }

    .btn-cetak {
        display: flex; align-items: center; justify-content: center; gap-6px;
        width: 100%; padding: 10px 18px; border-radius: 10px; border: none;
        font-size: .85rem; font-weight: 600; cursor: pointer;
        transition: opacity .15s, transform .1s;
        gap: 6px; margin-top: 20px;
    }
    .btn-cetak:hover { opacity: .88; transform: translateY(-1px); }
    .btn-cetak:active { transform: translateY(0); }
    .btn-cetak.cyan    { background: #0891b2; color: #fff; }
    .btn-cetak.violet  { background: #7c3aed; color: #fff; }
    .btn-cetak.emerald { background: #059669; color: #fff; }

    /* ── Shared Modal styles ── */
    .modal-print .modal-content {
        border-radius: 16px; border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,.15);
    }
    .modal-print .modal-header {
        border-radius: 16px 16px 0 0; padding: 16px 20px;
        border-bottom: none;
    }
    .modal-print .modal-header.cyan    { background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%); }
    .modal-print .modal-header.violet  { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); }
    .modal-print .modal-header.emerald { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
    .modal-print .modal-title { color: #fff; font-weight: 700; font-size: .95rem; }
    .modal-print .btn-close { filter: brightness(0) invert(1); }
    .modal-print .modal-body { padding: 20px; }
    .modal-print .modal-footer {
        border-top: 1px solid #f1f5f9; padding: 14px 20px;
        border-radius: 0 0 16px 16px;
    }

    /* Periode toggle pills */
    .periode-pills { display: flex; gap: 8px; margin-bottom: 14px; }
    .periode-pill {
        flex: 1; padding: 8px 0; border-radius: 10px; border: 1.5px solid #e2e8f0;
        background: #f8fafc; color: #64748b; font-size: .78rem; font-weight: 600;
        cursor: pointer; text-align: center; transition: all .15s; user-select: none;
    }
    .periode-pill.cyan.active    { background: #0891b2; color: #fff; border-color: #0891b2; box-shadow: 0 3px 10px rgba(8,145,178,.25); }
    .periode-pill.violet.active  { background: #7c3aed; color: #fff; border-color: #7c3aed; box-shadow: 0 3px 10px rgba(124,58,237,.25); }
    .periode-pill.emerald.active { background: #059669; color: #fff; border-color: #059669; box-shadow: 0 3px 10px rgba(5,150,105,.25); }
    .periode-pill:hover:not(.active) { border-color: #94a3b8; color: #334155; background: #f1f5f9; }

    .periode-section { display: none; }
    .periode-section.show { display: block; }

    .form-label-sm { font-size: .78rem; font-weight: 600; color: #475569; margin-bottom: 5px; display: block; }
    .form-control-print {
        width: 100%; border: 1.5px solid #e2e8f0; border-radius: 9px;
        padding: 8px 12px; font-size: .85rem; background: #f8fafc;
        color: #1e293b; outline: none; transition: border-color .15s;
    }
    .form-control-print:focus { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,.1); }
    .form-control-print.cyan:focus   { border-color: #0891b2; box-shadow: 0 0 0 3px rgba(8,145,178,.12); }
    .form-control-print.violet:focus  { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,.12); }
    .form-control-print.emerald:focus { border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,.12); }

    .info-cetak {
        border-radius: 9px; padding: 9px 13px; font-size: .78rem;
        margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
    }
    .info-cetak.cyan    { background: #f0f9ff; border: 1px solid #bae6fd; color: #0369a1; }
    .info-cetak.violet  { background: #f5f3ff; border: 1px solid #ddd6fe; color: #5b21b6; }
    .info-cetak.emerald { background: #f0fdf4; border: 1px solid #bbf7d0; color: #065f46; }

    .divider-label {
        font-size: .72rem; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: .06em;
        display: flex; align-items: center; gap: 8px; margin: 14px 0 10px;
    }
    .divider-label::before, .divider-label::after {
        content: ''; flex: 1; height: 1px; background: #e9ecef;
    }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-chart-bar"></i></div>
            <div>
                <h5 class="ph-title">Pusat Laporan</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dinsos.dashboard') }}">Dashboard</a></li>
                    <li><span class="bc-active">Laporan</span></li>
                </ol>
            </div>
        </div>
    </div>

    <div class="page-inner">

        <div class="row g-4">

            {{-- ── CARD 1: Laporan Donatur ── --}}
            <div class="col-md-4">
                <div class="laporan-card">
                    <div class="laporan-card-header cyan">
                        <div class="laporan-icon"><i class="fas fa-hand-holding-heart"></i></div>
                        <p class="laporan-title">Laporan Donatur</p>
                        <p class="laporan-subtitle">Rekap data donatur terdaftar</p>
                    </div>
                    <div class="laporan-card-body">
                        <p class="laporan-desc">
                            Cetak laporan daftar donatur berdasarkan periode harian, bulanan, atau tahunan.
                        </p>
                        <div class="laporan-feature"><i class="fas fa-check"></i> Filter per hari / bulan / tahun</div>
                        <div class="laporan-feature"><i class="fas fa-check"></i> Data status &amp; jenis donatur</div>
                        <div class="laporan-feature"><i class="fas fa-check"></i> Siap cetak &amp; simpan PDF</div>
                        <button class="btn-cetak cyan" data-bs-toggle="modal" data-bs-target="#modalDonatur">
                            <i class="fas fa-print"></i> Cetak Laporan Donatur
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── CARD 2: Laporan Donasi ── --}}
            <div class="col-md-4">
                <div class="laporan-card">
                    <div class="laporan-card-header violet">
                        <div class="laporan-icon"><i class="fas fa-donate"></i></div>
                        <p class="laporan-title">Laporan Donasi</p>
                        <p class="laporan-subtitle">Rekap penerimaan donasi masuk</p>
                    </div>
                    <div class="laporan-card-body">
                        <p class="laporan-desc">
                            Cetak laporan transaksi donasi berdasarkan periode dan filter panti atau status konfirmasi.
                        </p>
                        <div class="laporan-feature"><i class="fas fa-check"></i> Filter panti asuhan tertentu</div>
                        <div class="laporan-feature"><i class="fas fa-check"></i> Filter jenis &amp; status donasi</div>
                        <div class="laporan-feature"><i class="fas fa-check"></i> Filter per hari / bulan / tahun</div>
                        <button class="btn-cetak violet" data-bs-toggle="modal" data-bs-target="#modalDonasi">
                            <i class="fas fa-print"></i> Cetak Laporan Donasi
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── CARD 3: Laporan Keuangan ── --}}
            <div class="col-md-4">
                <div class="laporan-card">
                    <div class="laporan-card-header emerald">
                        <div class="laporan-icon"><i class="fas fa-money-bill-wave"></i></div>
                        <p class="laporan-title">Laporan Keuangan</p>
                        <p class="laporan-subtitle">Rekap pemasukan &amp; pengeluaran</p>
                    </div>
                    <div class="laporan-card-body">
                        <p class="laporan-desc">
                            Cetak laporan keuangan panti asuhan termasuk saldo, pemasukan, dan pengeluaran per periode.
                        </p>
                        <div class="laporan-feature"><i class="fas fa-check"></i> Pilih panti asuhan</div>
                        <div class="laporan-feature"><i class="fas fa-check"></i> Filter jenis transaksi</div>
                        <div class="laporan-feature"><i class="fas fa-check"></i> Filter per bulan / tahun</div>
                        <button class="btn-cetak emerald" data-bs-toggle="modal" data-bs-target="#modalKeuangan">
                            <i class="fas fa-print"></i> Cetak Laporan Keuangan
                        </button>
                    </div>
                </div>
            </div>

        </div>{{-- end row --}}

    </div>{{-- end page-inner --}}
</div>


{{-- ══════════════════════════════════════════
     MODAL 1 — CETAK LAPORAN DONATUR
══════════════════════════════════════════ --}}
<div class="modal fade modal-print" id="modalDonatur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header cyan">
                <span class="modal-title"><i class="fas fa-hand-holding-heart me-2"></i>Cetak Laporan Donatur</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="info-cetak cyan">
                    <i class="fas fa-info-circle"></i>
                    Laporan akan dibuka di tab baru dan siap dicetak atau disimpan sebagai PDF.
                </div>

                <label class="form-label-sm mb-2">Periode Laporan</label>
                <div class="periode-pills" id="pills-donatur">
                    <div class="periode-pill cyan active" data-target="donatur-harian">
                        <i class="fas fa-calendar-day d-block mb-1" style="font-size:.8rem;"></i>Per Hari
                    </div>
                    <div class="periode-pill cyan" data-target="donatur-bulanan">
                        <i class="fas fa-calendar-alt d-block mb-1" style="font-size:.8rem;"></i>Per Bulan
                    </div>
                    <div class="periode-pill cyan" data-target="donatur-tahunan">
                        <i class="fas fa-calendar d-block mb-1" style="font-size:.8rem;"></i>Per Tahun
                    </div>
                </div>

                <div class="periode-section show" id="donatur-harian">
                    <label class="form-label-sm">Pilih Tanggal</label>
                    <input type="date" id="dt-tanggal" class="form-control-print cyan"
                           value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="periode-section" id="donatur-bulanan">
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="form-label-sm">Bulan</label>
                            <select id="dt-bulan" class="form-control-print cyan">
                                @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                                          7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']
                                          as $num => $nama)
                                <option value="{{ $num }}" {{ $num == date('n') ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label-sm">Tahun</label>
                            <input type="number" id="dt-tahun-bulanan" class="form-control-print cyan"
                                   value="{{ date('Y') }}" min="2020" max="{{ date('Y') }}">
                        </div>
                    </div>
                </div>
                <div class="periode-section" id="donatur-tahunan">
                    <label class="form-label-sm">Pilih Tahun</label>
                    <input type="number" id="dt-tahun" class="form-control-print cyan"
                           value="{{ date('Y') }}" min="2020" max="{{ date('Y') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm" id="btnCetakDonatur"
                        style="background:#0891b2;color:#fff;min-width:110px;">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════
     MODAL 2 — CETAK LAPORAN DONASI
══════════════════════════════════════════ --}}
<div class="modal fade modal-print" id="modalDonasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content">
            <div class="modal-header violet">
                <span class="modal-title"><i class="fas fa-donate me-2"></i>Cetak Laporan Donasi</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="info-cetak violet">
                    <i class="fas fa-info-circle"></i>
                    Laporan akan dibuka di tab baru dan siap dicetak atau disimpan sebagai PDF.
                </div>

                {{-- Filter Panti --}}
                <div class="mb-3">
                    <label class="form-label-sm">Panti Asuhan</label>
                    <select id="ds-panti" class="form-control-print violet">
                        <option value="">Semua Panti</option>
                        @foreach($pantis as $panti)
                        <option value="{{ $panti->id }}">{{ $panti->nama_panti }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Jenis & Status --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label-sm">Jenis Donasi</label>
                        <select id="ds-jenis" class="form-control-print violet">
                            <option value="">Semua Jenis</option>
                            <option value="uang">Uang</option>
                            <option value="barang">Barang</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label-sm">Status</label>
                        <select id="ds-status" class="form-control-print violet">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>
                </div>

                <div class="divider-label">Periode</div>

                <div class="periode-pills" id="pills-donasi">
                    <div class="periode-pill violet active" data-target="donasi-harian">
                        <i class="fas fa-calendar-day d-block mb-1" style="font-size:.8rem;"></i>Per Hari
                    </div>
                    <div class="periode-pill violet" data-target="donasi-bulanan">
                        <i class="fas fa-calendar-alt d-block mb-1" style="font-size:.8rem;"></i>Per Bulan
                    </div>
                    <div class="periode-pill violet" data-target="donasi-tahunan">
                        <i class="fas fa-calendar d-block mb-1" style="font-size:.8rem;"></i>Per Tahun
                    </div>
                    <div class="periode-pill violet" data-target="donasi-semua">
                        <i class="fas fa-infinity d-block mb-1" style="font-size:.8rem;"></i>Semua
                    </div>
                </div>

                <div class="periode-section show" id="donasi-harian">
                    <label class="form-label-sm">Pilih Tanggal</label>
                    <input type="date" id="ds-tanggal" class="form-control-print violet"
                           value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                </div>
                <div class="periode-section" id="donasi-bulanan">
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="form-label-sm">Bulan</label>
                            <select id="ds-bulan" class="form-control-print violet">
                                @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                                          7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']
                                          as $num => $nama)
                                <option value="{{ $num }}" {{ $num == date('n') ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label-sm">Tahun</label>
                            <input type="number" id="ds-tahun-bulanan" class="form-control-print violet"
                                   value="{{ date('Y') }}" min="2020" max="{{ date('Y') }}">
                        </div>
                    </div>
                </div>
                <div class="periode-section" id="donasi-tahunan">
                    <label class="form-label-sm">Pilih Tahun</label>
                    <input type="number" id="ds-tahun" class="form-control-print violet"
                           value="{{ date('Y') }}" min="2020" max="{{ date('Y') }}">
                </div>
                <div class="periode-section" id="donasi-semua">
                    <p class="text-muted" style="font-size:.8rem; margin:0;">Semua data donasi akan ditampilkan tanpa filter tanggal.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm" id="btnCetakDonasi"
                        style="background:#7c3aed;color:#fff;min-width:110px;">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════
     MODAL 3 — CETAK LAPORAN KEUANGAN
══════════════════════════════════════════ --}}
<div class="modal fade modal-print" id="modalKeuangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content">
            <div class="modal-header emerald">
                <span class="modal-title"><i class="fas fa-money-bill-wave me-2"></i>Cetak Laporan Keuangan</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="info-cetak emerald">
                    <i class="fas fa-info-circle"></i>
                    Laporan akan dibuka di tab baru dan siap dicetak atau disimpan sebagai PDF.
                </div>

                {{-- Filter Panti --}}
                <div class="mb-3">
                    <label class="form-label-sm">Panti Asuhan <span style="color:#dc2626;">*</span></label>
                    <select id="ku-panti" class="form-control-print emerald">
                        <option value="">-- Pilih Panti Asuhan --</option>
                        @foreach($pantis as $panti)
                        <option value="{{ $panti->id }}">{{ $panti->nama_panti }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted" style="font-size:.72rem;">Wajib dipilih</small>
                </div>

                {{-- Filter Jenis --}}
                <div class="mb-3">
                    <label class="form-label-sm">Jenis Transaksi</label>
                    <select id="ku-jenis" class="form-control-print emerald">
                        <option value="">Semua (Pemasukan &amp; Pengeluaran)</option>
                        <option value="pemasukan">Pemasukan saja</option>
                        <option value="pengeluaran">Pengeluaran saja</option>
                    </select>
                </div>

                <div class="divider-label">Periode</div>

                <div class="periode-pills" id="pills-keuangan">
                    <div class="periode-pill emerald active" data-target="ku-bulanan">
                        <i class="fas fa-calendar-alt d-block mb-1" style="font-size:.8rem;"></i>Per Bulan
                    </div>
                    <div class="periode-pill emerald" data-target="ku-tahunan">
                        <i class="fas fa-calendar d-block mb-1" style="font-size:.8rem;"></i>Per Tahun
                    </div>
                    <div class="periode-pill emerald" data-target="ku-custom">
                        <i class="fas fa-sliders-h d-block mb-1" style="font-size:.8rem;"></i>Rentang
                    </div>
                </div>

                <div class="periode-section show" id="ku-bulanan">
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="form-label-sm">Bulan</label>
                            <select id="ku-bulan" class="form-control-print emerald">
                                @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                                          7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember']
                                          as $num => $nama)
                                <option value="{{ $num }}" {{ $num == date('n') ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label-sm">Tahun</label>
                            <input type="number" id="ku-tahun-bulanan" class="form-control-print emerald"
                                   value="{{ date('Y') }}" min="2020" max="{{ date('Y') }}">
                        </div>
                    </div>
                </div>
                <div class="periode-section" id="ku-tahunan">
                    <label class="form-label-sm">Pilih Tahun</label>
                    <input type="number" id="ku-tahun" class="form-control-print emerald"
                           value="{{ date('Y') }}" min="2020" max="{{ date('Y') }}">
                </div>
                <div class="periode-section" id="ku-custom">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label-sm">Dari Tanggal</label>
                            <input type="date" id="ku-dari" class="form-control-print emerald"
                                   value="{{ date('Y-m-01') }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label-sm">Sampai Tanggal</label>
                            <input type="date" id="ku-sampai" class="form-control-print emerald"
                                   value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm" id="btnCetakKeuangan"
                        style="background:#059669;color:#fff;min-width:110px;">
                    <i class="fas fa-print me-1"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// ══════════════════════════════════════════
// Helper: buat sistem pills per modal
// ══════════════════════════════════════════
function initPills(pillsContainerId, defaultSection) {
    const container = document.getElementById(pillsContainerId);
    if (!container) return;
    let current = defaultSection;

    container.querySelectorAll('.periode-pill').forEach(pill => {
        pill.addEventListener('click', function () {
            container.querySelectorAll('.periode-pill').forEach(p => p.classList.remove('active'));
            // Sembunyikan semua section milik modal ini
            const target = this.dataset.target;
            document.querySelectorAll(`[id^="${target.split('-')[0]}-"]`).forEach(s => {
                if (s.classList.contains('periode-section')) s.classList.remove('show');
            });
            this.classList.add('active');
            current = target;
            document.getElementById(current)?.classList.add('show');
        });
    });

    // Reset saat modal dibuka
    const modalEl = container.closest('.modal');
    modalEl?.addEventListener('show.bs.modal', function () {
        container.querySelectorAll('.periode-pill').forEach(p => p.classList.remove('active'));
        container.querySelector('.periode-pill')?.classList.add('active');
        current = defaultSection;
        // Semua section off dulu
        modalEl.querySelectorAll('.periode-section').forEach(s => s.classList.remove('show'));
        document.getElementById(defaultSection)?.classList.add('show');
    });

    return () => current;
}

// ── Init masing-masing modal ──
const getDonaturPeriode  = initPills('pills-donatur',  'donatur-harian');
const getDonasiPeriode   = initPills('pills-donasi',   'donasi-harian');
const getKeuanganPeriode = initPills('pills-keuangan', 'ku-bulanan');

// Tracker periode aktif sederhana (karena closure di atas tidak bisa di-return dengan mudah)
let currentDonatur  = 'donatur-harian';
let currentDonasi   = 'donasi-harian';
let currentKeuangan = 'ku-bulanan';

document.getElementById('pills-donatur')?.querySelectorAll('.periode-pill').forEach(p => {
    p.addEventListener('click', () => { currentDonatur = p.dataset.target; });
});
document.getElementById('pills-donasi')?.querySelectorAll('.periode-pill').forEach(p => {
    p.addEventListener('click', () => { currentDonasi = p.dataset.target; });
});
document.getElementById('pills-keuangan')?.querySelectorAll('.periode-pill').forEach(p => {
    p.addEventListener('click', () => { currentKeuangan = p.dataset.target; });
});

document.getElementById('modalDonatur')?.addEventListener('show.bs.modal',  () => { currentDonatur  = 'donatur-harian'; });
document.getElementById('modalDonasi')?.addEventListener('show.bs.modal',   () => { currentDonasi   = 'donasi-harian'; });
document.getElementById('modalKeuangan')?.addEventListener('show.bs.modal', () => { currentKeuangan = 'ku-bulanan'; });


// ══════════════════════════════════════════
// Tombol Cetak Donatur
// ══════════════════════════════════════════
document.getElementById('btnCetakDonatur')?.addEventListener('click', function () {
    let url = '';
    const base = `{{ route('donatur.laporan') }}`;

    if (currentDonatur === 'donatur-harian') {
        const t = document.getElementById('dt-tanggal').value;
        if (!t) { alert('Pilih tanggal terlebih dahulu.'); return; }
        url = `${base}?periode=harian&tanggal=${t}`;

    } else if (currentDonatur === 'donatur-bulanan') {
        const b = document.getElementById('dt-bulan').value;
        const y = document.getElementById('dt-tahun-bulanan').value;
        if (!b || !y) { alert('Pilih bulan dan tahun terlebih dahulu.'); return; }
        url = `${base}?periode=bulanan&bulan=${b}&tahun=${y}`;

    } else if (currentDonatur === 'donatur-tahunan') {
        const y = document.getElementById('dt-tahun').value;
        if (!y) { alert('Pilih tahun terlebih dahulu.'); return; }
        url = `${base}?periode=tahunan&tahun=${y}`;
    }

    window.open(url, '_blank');
    bootstrap.Modal.getInstance(document.getElementById('modalDonatur')).hide();
});


// ══════════════════════════════════════════
// Tombol Cetak Donasi
// ══════════════════════════════════════════
document.getElementById('btnCetakDonasi')?.addEventListener('click', function () {
    const base   = `{{ route('donasi.print') }}`;
    const panti  = document.getElementById('ds-panti').value;
    const jenis  = document.getElementById('ds-jenis').value;
    const status = document.getElementById('ds-status').value;

    const params = new URLSearchParams();
    if (panti)  params.set('panti_asuhan_id', panti);
    if (jenis)  params.set('jenis_donasi', jenis);
    if (status) params.set('status', status);

    if (currentDonasi === 'donasi-harian') {
        const t = document.getElementById('ds-tanggal').value;
        if (!t) { alert('Pilih tanggal terlebih dahulu.'); return; }
        params.set('periode', 'harian');
        params.set('tanggal', t);

    } else if (currentDonasi === 'donasi-bulanan') {
        const b = document.getElementById('ds-bulan').value;
        const y = document.getElementById('ds-tahun-bulanan').value;
        if (!b || !y) { alert('Pilih bulan dan tahun terlebih dahulu.'); return; }
        params.set('periode', 'bulanan');
        params.set('bulan', b);
        params.set('tahun', y);

    } else if (currentDonasi === 'donasi-tahunan') {
        const y = document.getElementById('ds-tahun').value;
        if (!y) { alert('Pilih tahun terlebih dahulu.'); return; }
        params.set('periode', 'tahunan');
        params.set('tahun', y);

    } else if (currentDonasi === 'donasi-semua') {
        params.set('periode', 'semua');
    }

    window.open(`${base}?${params.toString()}`, '_blank');
    bootstrap.Modal.getInstance(document.getElementById('modalDonasi')).hide();
});


// ══════════════════════════════════════════
// Tombol Cetak Keuangan
// ══════════════════════════════════════════
document.getElementById('btnCetakKeuangan')?.addEventListener('click', function () {
    const base  = `{{ route('keuangan.laporan.cetak') }}`;
    const panti = document.getElementById('ku-panti').value;
    const jenis = document.getElementById('ku-jenis').value;

    if (!panti) { alert('Pilih panti asuhan terlebih dahulu.'); return; }

    const params = new URLSearchParams();
    params.set('panti_asuhan_id', panti);
    if (jenis) params.set('jenis', jenis);

    if (currentKeuangan === 'ku-bulanan') {
        const b = document.getElementById('ku-bulan').value;
        const y = document.getElementById('ku-tahun-bulanan').value;
        if (!b || !y) { alert('Pilih bulan dan tahun terlebih dahulu.'); return; }
        params.set('periode', 'bulanan');
        params.set('bulan', b);
        params.set('tahun', y);

    } else if (currentKeuangan === 'ku-tahunan') {
        const y = document.getElementById('ku-tahun').value;
        if (!y) { alert('Pilih tahun terlebih dahulu.'); return; }
        params.set('periode', 'tahunan');
        params.set('tahun', y);

    } else if (currentKeuangan === 'ku-custom') {
        const dari   = document.getElementById('ku-dari').value;
        const sampai = document.getElementById('ku-sampai').value;
        if (!dari || !sampai) { alert('Isi rentang tanggal terlebih dahulu.'); return; }
        if (dari > sampai)    { alert('Tanggal awal tidak boleh melebihi tanggal akhir.'); return; }
        params.set('periode', 'custom');
        params.set('dari', dari);
        params.set('sampai', sampai);
    }

    window.open(`${base}?${params.toString()}`, '_blank');
    bootstrap.Modal.getInstance(document.getElementById('modalKeuangan')).hide();
});
</script>
@endsection
