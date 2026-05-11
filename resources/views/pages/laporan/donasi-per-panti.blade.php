@extends('layouts.user.user')

@section('title', 'Laporan Donasi Per Panti')

@push('styles')
<style>
.stat-card { border:none; border-radius:16px; transition:transform .18s,box-shadow .18s; }
.stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.12) !important; }
.stat-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }

.tbl-laporan thead th {
    background:#1565C0; color:#fff; font-size:.78rem;
    text-transform:uppercase; letter-spacing:.04em;
    border:none; padding:12px 14px; white-space:nowrap; text-align:center;
}
.tbl-laporan tbody tr { transition:background .12s; }
.tbl-laporan tbody tr:hover { background:#f0f7ff !important; }
.tbl-laporan td { vertical-align:middle; font-size:.88rem; padding:10px 14px; }

/* Mini progress badges */
.mini-badge { display:inline-flex; align-items:center; gap:3px; padding:2px 8px; border-radius:12px; font-size:.72rem; font-weight:600; }
.mb-terima  { background:#e8f5e9; color:#2e7d32; }
.mb-pending { background:#fff8e1; color:#f57f17; }
.mb-tolak   { background:#ffebee; color:#c62828; }

/* Pill besar --*/
.pill-uang   { background:#e3f2fd; color:#1565C0; border:1px solid #bbdefb; }
.pill-barang { background:#f3e5f5; color:#6a1b9a; border:1px solid #ce93d8; }
.jml-pill    { display:inline-flex; align-items:center; gap:4px; padding:4px 12px; border-radius:20px; font-size:.8rem; font-weight:600; }

/* Grand total row */
.tfoot-grand td { background:#1565C0 !important; color:#fff !important; font-weight:700; }

@media print {
    .laporan-filter-card, .btn-print, .sidebar, nav, .breadcrumb-wrap { display:none !important; }
    .card { box-shadow:none !important; border:1px solid #dee2e6 !important; }
    .tbl-laporan thead th { background:#1565C0 !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    .tfoot-grand td { background:#1565C0 !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- ── Header ───────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="bi bi-buildings-fill me-2 text-primary"></i>Laporan Donasi Per Panti Asuhan
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('dinsos.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan Donasi Per Panti</li>
                </ol>
            </nav>
        </div>
        <button class="btn btn-outline-primary btn-print d-flex align-items-center gap-2" onclick="window.print()">
            <i class="bi bi-printer"></i><span class="d-none d-sm-inline">Cetak</span>
        </button>
    </div>

    {{-- ── Filter ────────────────────────────────────── --}}
    @php
        $filterTambahan = '
        <div class="col-md-4">
            <label class="form-label fw-semibold small">Status Donasi</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="pending"  '.( request("status")=="pending"  ? "selected" : "" ).'>⏳ Pending</option>
                <option value="diterima" '.( request("status")=="diterima" ? "selected" : "" ).'>✓ Diterima</option>
                <option value="ditolak"  '.( request("status")=="ditolak"  ? "selected" : "" ).'>✗ Ditolak</option>
            </select>
        </div>';
    @endphp
    @include('pages.laporan._filter-periode')

    {{-- ── Periode info ─────────────────────────────── --}}
    <div class="alert alert-primary border-0 d-flex align-items-center gap-2 py-2 px-3 mb-4 rounded-3 small">
        <i class="bi bi-info-circle-fill"></i>
        Periode: <strong>{{ $startCarbon->translatedFormat('d F Y') }}</strong>
        s/d <strong>{{ $endCarbon->translatedFormat('d F Y') }}</strong>
        @if(request('status'))
            &nbsp;|&nbsp; Filter status: <strong>{{ ucfirst(request('status')) }}</strong>
        @endif
    </div>

    {{-- ── Summary Cards ────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#e3f2fd,#bbdefb);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:#1565C0;color:#fff;"><i class="bi bi-house-heart-fill"></i></div>
                    <div>
                        <div class="small fw-semibold text-primary">Panti Aktif</div>
                        <div class="fw-bold fs-5">{{ $data->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:#2e7d32;color:#fff;"><i class="bi bi-cash-coin"></i></div>
                    <div>
                        <div class="small fw-semibold text-success">Total Nominal</div>
                        <div class="fw-bold" style="font-size:1rem;">Rp {{ number_format($grandTotal['total_nominal'], 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#fff3e0,#ffe0b2);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:#e65100;color:#fff;"><i class="bi bi-currency-exchange"></i></div>
                    <div>
                        <div class="small fw-semibold text-warning">Donasi Uang</div>
                        <div class="fw-bold fs-5">{{ $grandTotal['jml_donasi_uang'] }} <span class="small fw-normal">tx</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#f3e5f5,#e1bee7);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:#6a1b9a;color:#fff;"><i class="bi bi-box-seam-fill"></i></div>
                    <div>
                        <div class="small fw-semibold" style="color:#6a1b9a;">Donasi Barang</div>
                        <div class="fw-bold fs-5">{{ $grandTotal['jml_donasi_barang'] }} <span class="small fw-normal">tx</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabel ─────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <span class="fw-semibold">
                <i class="bi bi-table me-1 text-primary"></i>Rekap Donasi Per Panti Asuhan
            </span>
            <div class="d-flex align-items-center gap-2">
                <span class="mini-badge mb-terima"><i class="bi bi-check-circle-fill"></i> {{ $grandTotal['jml_diterima'] }} diterima</span>
                <span class="mini-badge mb-pending"><i class="bi bi-hourglass-split"></i> {{ $grandTotal['jml_pending'] }} pending</span>
                <span class="mini-badge mb-tolak"><i class="bi bi-x-circle-fill"></i> {{ $grandTotal['jml_ditolak'] }} ditolak</span>
            </div>
        </div>
        <div class="card-body p-0">
            @if($data->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-4 d-block mb-3 opacity-30"></i>
                    Tidak ada data donasi pada periode ini.
                </div>
            @else
            <div class="table-responsive">
                <table class="table tbl-laporan mb-0">
                    <thead>
                        <tr>
                            <th style="width:48px;text-align:center;">No</th>
                            <th style="text-align:left;">Nama Panti Asuhan</th>
                            <th>Kecamatan</th>
                            <th>Donasi Uang</th>
                            <th>Total Nominal</th>
                            <th>Donasi Barang</th>
                            <th>Total Donasi</th>
                            <th>Status Breakdown</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $i => $row)
                        <tr>
                            <td class="text-center text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $row['panti']->nama_panti }}</div>
                                <div class="small text-muted">{{ $row['panti']->alamat }}</div>
                            </td>
                            <td class="text-center">
                                <span class="small text-secondary">{{ $row['panti']->kecamatan ?? '—' }}</span>
                            </td>
                            <td class="text-center">
                                @if($row['jml_donasi_uang'] > 0)
                                    <span class="jml-pill pill-uang">
                                        <i class="bi bi-cash"></i> {{ $row['jml_donasi_uang'] }} donasi
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($row['total_nominal'] > 0)
                                    <span class="fw-bold text-success">
                                        Rp {{ number_format($row['total_nominal'], 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($row['jml_donasi_barang'] > 0)
                                    <span class="jml-pill pill-barang">
                                        <i class="bi bi-box"></i> {{ $row['jml_donasi_barang'] }} donasi
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="fw-bold">{{ $row['total_donasi'] }}</span>
                                <span class="small text-muted">transaksi</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap justify-content-center">
                                    @if($row['jml_diterima'] > 0)
                                        <span class="mini-badge mb-terima">
                                            <i class="bi bi-check-circle-fill"></i>{{ $row['jml_diterima'] }}
                                        </span>
                                    @endif
                                    @if($row['jml_pending'] > 0)
                                        <span class="mini-badge mb-pending">
                                            <i class="bi bi-hourglass-split"></i>{{ $row['jml_pending'] }}
                                        </span>
                                    @endif
                                    @if($row['jml_ditolak'] > 0)
                                        <span class="mini-badge mb-tolak">
                                            <i class="bi bi-x-circle-fill"></i>{{ $row['jml_ditolak'] }}
                                        </span>
                                    @endif
                                    @if($row['total_donasi'] == 0)
                                        <span class="text-muted small">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="tfoot-grand">
                        <tr>
                            <td colspan="3" class="text-end" style="color:#fff !important;">TOTAL KESELURUHAN</td>
                            <td class="text-center" style="color:#fff !important;">
                                {{ $grandTotal['jml_donasi_uang'] }} donasi
                            </td>
                            <td class="text-center" style="color:#fff !important;">
                                Rp {{ number_format($grandTotal['total_nominal'], 0, ',', '.') }}
                            </td>
                            <td class="text-center" style="color:#fff !important;">
                                {{ $grandTotal['jml_donasi_barang'] }} donasi
                            </td>
                            <td class="text-center" style="color:#fff !important;">
                                {{ $grandTotal['total_donasi'] }} transaksi
                            </td>
                            <td class="text-center" style="color:#fff !important;">
                                ✓{{ $grandTotal['jml_diterima'] }}
                                ⏳{{ $grandTotal['jml_pending'] }}
                                ✗{{ $grandTotal['jml_ditolak'] }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>

        {{-- Legend --}}
        <div class="card-footer bg-white border-top py-3 d-print-none">
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <span class="small text-muted fw-semibold">Keterangan:</span>
                <span class="mini-badge mb-terima"><i class="bi bi-check-circle-fill"></i> Diterima</span>
                <span class="mini-badge mb-pending"><i class="bi bi-hourglass-split"></i> Pending</span>
                <span class="mini-badge mb-tolak"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                <span class="jml-pill pill-uang ms-2"><i class="bi bi-cash"></i> Donasi Uang</span>
                <span class="jml-pill pill-barang"><i class="bi bi-box"></i> Donasi Barang</span>
                <span class="small text-muted">* Nominal hanya dari donasi uang berstatus <strong>Diterima</strong></span>
            </div>
        </div>
        <div class="card-footer bg-white border-top-0 pt-0 d-none d-print-block small text-muted text-center">
            Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} &nbsp;|&nbsp;
            Sistem Informasi Donasi Panti Asuhan – Dinas Sosial
        </div>
    </div>

</div>
@endsection
