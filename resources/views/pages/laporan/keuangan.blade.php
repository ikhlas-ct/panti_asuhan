@extends('layouts.user.user')


@section('title', 'Laporan Keuangan')

@push('styles')
<style>
.stat-card { border:none; border-radius:16px; transition:transform .18s, box-shadow .18s; }
.stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.12) !important; }
.stat-icon { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; }

.tbl-laporan thead th {
    background:#1565C0; color:#fff; font-size:.78rem;
    text-transform:uppercase; letter-spacing:.04em;
    border:none; padding:12px 14px; white-space:nowrap;
}
.tbl-laporan tbody tr { transition:background .12s; }
.tbl-laporan tbody tr:hover { background:#f0f7ff !important; }
.tbl-laporan td { vertical-align:middle; font-size:.88rem; padding:10px 14px; }

.badge-pemasukan { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
.badge-pengeluaran { background:#ffebee; color:#c62828; border:1px solid #ef9a9a; }
.jenis-badge { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:20px; font-size:.78rem; font-weight:600; }

.saldo-positif { color:#2e7d32; }
.saldo-negatif { color:#c62828; }

@media print {
    .laporan-filter-card, .btn-print, .sidebar, nav, .breadcrumb-wrap { display:none !important; }
    .card { box-shadow:none !important; border:1px solid #dee2e6 !important; }
    .tbl-laporan thead th { background:#1565C0 !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    .stat-card { border:1px solid #dee2e6 !important; }
}
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    {{-- ── Header ───────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>Laporan Keuangan
                @if($selectedPanti)
                    <small class="text-muted fw-normal fs-6">— {{ $selectedPanti->nama_panti }}</small>
                @endif
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    @auth
                        @if(auth()->user()->isAdminPanti())
                            <li class="breadcrumb-item"><a href="{{ route('admin_panti.dashboard') }}">Dashboard</a></li>
                        @else
                            <li class="breadcrumb-item"><a href="{{ route('dinsos.dashboard') }}">Dashboard</a></li>
                        @endif
                    @endauth
                    <li class="breadcrumb-item active">Laporan Keuangan</li>
                </ol>
            </nav>
        </div>
        <button class="btn btn-outline-primary btn-print d-flex align-items-center gap-2" onclick="window.print()">
            <i class="bi bi-printer"></i><span class="d-none d-sm-inline">Cetak</span>
        </button>
    </div>

    {{-- ── Filter ────────────────────────────────────── --}}
    @php
        $extraOptions = '';
        // Panti filter (hanya admin_dinsos)
        if(auth()->user()->isAdminDinsos()):
            $extraOptions .= '<div class="col-md-5">
                <label class="form-label fw-semibold small">Panti Asuhan</label>
                <select name="panti_asuhan_id" class="form-select form-select-sm">
                    <option value="">Semua Panti</option>';
            foreach($pantis as $p):
                $sel = (request('panti_asuhan_id') == $p->id) ? 'selected' : '';
                $extraOptions .= '<option value="'.$p->id.'" '.$sel.'>'.$p->nama_panti.'</option>';
            endforeach;
            $extraOptions .= '</select></div>';
        endif;

        $extraOptions .= '<div class="col-md-3">
            <label class="form-label fw-semibold small">Jenis Transaksi</label>
            <select name="jenis" class="form-select form-select-sm">
                <option value="">Semua Jenis</option>
                <option value="pemasukan"   '.(request("jenis")=="pemasukan"   ? "selected":"").'>↑ Pemasukan</option>
                <option value="pengeluaran" '.(request("jenis")=="pengeluaran" ? "selected":"").'>↓ Pengeluaran</option>
            </select>
        </div>';

        $filterTambahan = $extraOptions;
    @endphp
    @include('pages.laporan._filter-periode')

    {{-- ── Periode info ─────────────────────────────── --}}
    <div class="alert alert-primary border-0 d-flex align-items-center gap-2 py-2 px-3 mb-4 rounded-3 small">
        <i class="bi bi-info-circle-fill"></i>
        Periode: <strong>{{ $startCarbon->translatedFormat('d F Y') }}</strong>
        s/d <strong>{{ $endCarbon->translatedFormat('d F Y') }}</strong>
        @if($selectedPanti)
            &nbsp;|&nbsp; Panti: <strong>{{ $selectedPanti->nama_panti }}</strong>
        @endif
    </div>

    {{-- ── Summary Cards ────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:#2e7d32;color:#fff;"><i class="bi bi-arrow-up-circle-fill"></i></div>
                    <div>
                        <div class="small fw-semibold text-success mb-1">Total Pemasukan</div>
                        <div class="fw-bold" style="font-size:1.2rem;">
                            Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100" style="background:linear-gradient(135deg,#ffebee,#ffcdd2);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:#c62828;color:#fff;"><i class="bi bi-arrow-down-circle-fill"></i></div>
                    <div>
                        <div class="small fw-semibold text-danger mb-1">Total Pengeluaran</div>
                        <div class="fw-bold" style="font-size:1.2rem;">
                            Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100"
                 style="background:linear-gradient(135deg,{{ $summary['saldo'] >= 0 ? '#e3f2fd,#bbdefb' : '#ffebee,#ffcdd2' }});">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon" style="background:{{ $summary['saldo'] >= 0 ? '#1565C0' : '#c62828' }};color:#fff;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <div class="small fw-semibold mb-1" style="color:{{ $summary['saldo'] >= 0 ? '#1565C0' : '#c62828' }};">
                            Saldo
                        </div>
                        <div class="fw-bold {{ $summary['saldo'] >= 0 ? 'saldo-positif' : 'saldo-negatif' }}" style="font-size:1.2rem;">
                            {{ $summary['saldo'] < 0 ? '- ' : '' }}Rp {{ number_format(abs($summary['saldo']), 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabel ─────────────────────────────────────── --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <span class="fw-semibold">
                <i class="bi bi-table me-1 text-primary"></i>Detail Transaksi Keuangan
            </span>
            <span class="badge bg-primary rounded-pill">{{ $keuangan->count() }} data</span>
        </div>
        <div class="card-body p-0">
            @if($keuangan->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-4 d-block mb-3 opacity-30"></i>
                    Tidak ada data keuangan pada periode ini.
                </div>
            @else
            <div class="table-responsive">
                <table class="table tbl-laporan mb-0">
                    <thead>
                        <tr>
                            <th style="width:48px">No</th>
                            <th>Tanggal</th>
                            @if(auth()->user()->isAdminDinsos() && !$selectedPantiId)
                                <th>Panti Asuhan</th>
                            @endif
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-end">Nominal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($keuangan as $i => $k)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td class="text-nowrap">
                                <div class="fw-semibold">{{ $k->tanggal->format('d M Y') }}</div>
                            </td>
                            @if(auth()->user()->isAdminDinsos() && !$selectedPantiId)
                                <td>
                                    <div class="fw-semibold">{{ optional($k->pantiAsuhan)->nama_panti ?? '-' }}</div>
                                </td>
                            @endif
                            <td>
                                <span class="badge rounded-pill px-3" style="background:#f3f4f6;color:#374151;font-size:.75rem;">
                                    {{ $k->kategori ?? '—' }}
                                </span>
                            </td>
                            <td>
                                {{ $k->keterangan ?? '—' }}
                                @if($k->donasi)
                                    <div class="small text-muted">
                                        <i class="bi bi-link-45deg"></i>
                                        Dari donasi #{{ $k->donasi->id }}
                                        ({{ optional($k->donasi->donatur)->nama ?? '?' }})
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($k->jenis === 'pemasukan')
                                    <span class="jenis-badge badge-pemasukan">
                                        <i class="bi bi-arrow-up-circle-fill"></i> Pemasukan
                                    </span>
                                @else
                                    <span class="jenis-badge badge-pengeluaran">
                                        <i class="bi bi-arrow-down-circle-fill"></i> Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold {{ $k->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                {{ $k->jenis === 'pengeluaran' ? '(' : '' }}{{ number_format($k->nominal, 0, ',', '.') }}{{ $k->jenis === 'pengeluaran' ? ')' : '' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#e8f5e9;">
                            <td colspan="{{ (auth()->user()->isAdminDinsos() && !$selectedPantiId) ? 6 : 5 }}"
                                class="text-end fw-bold text-success">Total Pemasukan:</td>
                            <td class="text-end fw-bold text-success">{{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</td>
                        </tr>
                        <tr style="background:#ffebee;">
                            <td colspan="{{ (auth()->user()->isAdminDinsos() && !$selectedPantiId) ? 6 : 5 }}"
                                class="text-end fw-bold text-danger">Total Pengeluaran:</td>
                            <td class="text-end fw-bold text-danger">({{ number_format($summary['total_pengeluaran'], 0, ',', '.') }})</td>
                        </tr>
                        <tr style="background:#1565C0;">
                            <td colspan="{{ (auth()->user()->isAdminDinsos() && !$selectedPantiId) ? 6 : 5 }}"
                                class="text-end fw-bold text-white">SALDO:</td>
                            <td class="text-end fw-bold text-white">
                                {{ $summary['saldo'] < 0 ? '(' : '' }}{{ number_format(abs($summary['saldo']), 0, ',', '.') }}{{ $summary['saldo'] < 0 ? ')' : '' }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
        <div class="card-footer bg-white border-top-0 pt-3 d-none d-print-block small text-muted text-center">
            Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} &nbsp;|&nbsp;
            Sistem Informasi Donasi Panti Asuhan – Dinas Sosial
        </div>
    </div>

</div>
@endsection
