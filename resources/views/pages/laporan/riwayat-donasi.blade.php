@extends('layouts.user.user')

@section('title', 'Riwayat Donasi Saya')

@push('styles')
<style>
/* ── Card stat ──────────────────────────────────── */
.stat-card {
    border: none;
    border-radius: 16px;
    transition: transform .18s, box-shadow .18s;
}
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12) !important; }
.stat-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
}

/* ── Table ──────────────────────────────────────── */
.tbl-laporan thead th {
    background: #1565C0; color: #fff; font-size: .78rem;
    text-transform: uppercase; letter-spacing: .04em;
    border: none; padding: 12px 14px; white-space: nowrap;
}
.tbl-laporan tbody tr { transition: background .12s; }
.tbl-laporan tbody tr:hover { background: #f0f7ff !important; }
.tbl-laporan td { vertical-align: middle; font-size: .88rem; padding: 10px 14px; }

/* ── Badge status ──────────────────────────────── */
.badge-diterima { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
.badge-pending  { background:#fff8e1; color:#f57f17; border:1px solid #ffe082; }
.badge-ditolak  { background:#ffebee; color:#c62828; border:1px solid #ef9a9a; }
.status-badge {
    display:inline-flex; align-items:center; gap:5px;
    padding: 4px 10px; border-radius: 20px; font-size: .78rem; font-weight: 600;
}

/* ── Print ──────────────────────────────────────── */
@media print {
    .laporan-filter-card, .btn-print, .sidebar, nav, .breadcrumb-wrap { display: none !important; }
    .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
    .tbl-laporan thead th { background: #1565C0 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- ── Breadcrumb ──────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="bi bi-receipt-cutoff me-2 text-primary"></i>Riwayat Donasi Saya
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('donatur.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Riwayat Donasi</li>
                </ol>
            </nav>
        </div>
        <button class="btn btn-outline-primary btn-print d-flex align-items-center gap-2" onclick="window.print()">
            <i class="bi bi-printer"></i> <span class="d-none d-sm-inline">Cetak</span>
        </button>
    </div>

    {{-- ── Filter Periode ───────────────────────────── --}}
    @php
        $filterTambahan = '
        <div class="col-md-4">
            <label class="form-label fw-semibold small">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="pending"  '.( request("status")=="pending"  ? "selected" : "" ).'>⏳ Pending</option>
                <option value="diterima" '.( request("status")=="diterima" ? "selected" : "" ).'>✓ Diterima</option>
                <option value="ditolak"  '.( request("status")=="ditolak"  ? "selected" : "" ).'>✗ Ditolak</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold small">Jenis Donasi</label>
            <select name="jenis_donasi" class="form-select form-select-sm">
                <option value="">Semua Jenis</option>
                <option value="uang"   '.( request("jenis_donasi")=="uang"   ? "selected" : "" ).'>💰 Uang</option>
                <option value="barang" '.( request("jenis_donasi")=="barang" ? "selected" : "" ).'>📦 Barang</option>
            </select>
        </div>';
    @endphp
    @include('pages.laporan._filter-periode')

    {{-- ── Periode aktif info ───────────────────────── --}}
    <div class="alert alert-primary border-0 d-flex align-items-center gap-2 py-2 px-3 mb-4 rounded-3 small">
        <i class="bi bi-info-circle-fill"></i>
        Menampilkan donasi periode
        <strong>{{ $startCarbon->translatedFormat('d F Y') }}</strong>
        s/d
        <strong>{{ $endCarbon->translatedFormat('d F Y') }}</strong>
        &nbsp;—&nbsp; Total: <strong>{{ $summary['total_semua'] }} transaksi</strong>
    </div>

    {{-- ── Summary Cards ────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:#2e7d32;color:#fff;">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div>
                        <div class="small fw-semibold text-success">Total Uang Diterima</div>
                        <div class="fw-bold fs-6">Rp {{ number_format($summary['total_uang_diterima'],0,',','.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#e3f2fd,#bbdefb);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:#1565C0;color:#fff;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <div class="small fw-semibold text-primary">Barang Diterima</div>
                        <div class="fw-bold fs-6">{{ $summary['jml_barang_diterima'] }} <span class="fw-normal small">donasi</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#fff8e1,#ffe082);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:#f9a825;color:#fff;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="small fw-semibold text-warning">Menunggu</div>
                        <div class="fw-bold fs-6">{{ $summary['jml_pending'] }} <span class="fw-normal small">pending</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#ffebee,#ffcdd2);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:#c62828;color:#fff;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <div class="small fw-semibold text-danger">Ditolak</div>
                        <div class="fw-bold fs-6">{{ $summary['jml_ditolak'] }} <span class="fw-normal small">donasi</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabel ─────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <span class="fw-semibold">
                <i class="bi bi-table me-1 text-primary"></i>Detail Riwayat Donasi
            </span>
            <span class="badge bg-primary rounded-pill">{{ $donasi->count() }} data</span>
        </div>
        <div class="card-body p-0">
            @if($donasi->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-4 d-block mb-3 opacity-30"></i>
                    Tidak ada donasi pada periode ini.
                </div>
            @else
            <div class="table-responsive">
                <table class="table tbl-laporan mb-0">
                    <thead>
                        <tr>
                            <th style="width:48px">No</th>
                            <th>Tanggal</th>
                            <th>Nama Panti Asuhan</th>
                            <th>Jenis</th>
                            <th>Metode</th>
                            <th>Nominal / Keterangan</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donasi as $i => $d)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td class="text-nowrap">
                                <div class="fw-semibold">{{ $d->tanggal_donasi->format('d M Y') }}</div>
                                @if($d->metode === 'kunjungan' && $d->tanggal_kunjungan)
                                    <div class="small text-muted">Kunjungan: {{ $d->tanggal_kunjungan->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $d->pantiAsuhan->nama_panti ?? '-' }}</div>
                                <div class="small text-muted">{{ $d->pantiAsuhan->kecamatan ?? '' }}</div>
                            </td>
                            <td>
                                @if($d->jenis_donasi === 'uang')
                                    <span class="badge text-bg-success-subtle rounded-pill px-3" style="background:#e8f5e9;color:#2e7d32;border:1px solid #c8e6c9;">
                                        <i class="bi bi-cash me-1"></i>Uang
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3" style="background:#e3f2fd;color:#1565C0;border:1px solid #bbdefb;">
                                        <i class="bi bi-box me-1"></i>Barang
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($d->metode === 'online')
                                    <i class="bi bi-wifi text-info me-1"></i>Online
                                @else
                                    <i class="bi bi-person-walking text-secondary me-1"></i>Kunjungan
                                @endif
                            </td>
                            <td>
                                @if($d->jenis_donasi === 'uang')
                                    <span class="fw-semibold text-success">Rp {{ number_format($d->nominal, 0, ',', '.') }}</span>
                                @else
                                    <span>{{ Str::limit($d->deskripsi_barang ?? '-', 50) }}</span>
                                    @if($d->barang->count())
                                        <div class="small text-muted">{{ $d->barang->count() }} item diterima</div>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                @if($d->status === 'diterima')
                                    <span class="status-badge badge-diterima">
                                        <i class="bi bi-check-circle-fill"></i> Diterima
                                    </span>
                                @elseif($d->status === 'pending')
                                    <span class="status-badge badge-pending">
                                        <i class="bi bi-hourglass-split"></i> Pending
                                    </span>
                                @else
                                    <span class="status-badge badge-ditolak">
                                        <i class="bi bi-x-circle-fill"></i> Ditolak
                                    </span>
                                @endif
                                @if($d->status === 'ditolak' && $d->alasan_tolak)
                                    <div class="small text-muted mt-1" title="{{ $d->alasan_tolak }}">
                                        {{ Str::limit($d->alasan_tolak, 30) }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#e3f2fd;">
                            <td colspan="5" class="text-end fw-bold text-primary">Total Donasi Uang Diterima :</td>
                            <td class="fw-bold text-success">Rp {{ number_format($summary['total_uang_diterima'], 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
        {{-- Print footer (hanya muncul saat print) --}}
        <div class="card-footer bg-white border-top-0 pt-3 d-none d-print-block small text-muted text-center">
            Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} &nbsp;|&nbsp;
            Sistem Informasi Donasi Panti Asuhan – Dinas Sosial
        </div>
    </div>

</div>
@endsection
