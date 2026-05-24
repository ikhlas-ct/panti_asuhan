@extends('layouts.user.user')

@section('title', 'Data Keuangan')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body, .card, .table, .btn, h4, h5 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border: none; border-radius: 16px; padding: 20px;
            position: relative; overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
        .stat-card::after {
            content: ''; position: absolute; right: -18px; top: -18px;
            width: 80px; height: 80px; border-radius: 50%; opacity: .12;
        }
        .stat-card.blue  { background: linear-gradient(135deg,#e8f0fe,#dbeafe); }
        .stat-card.blue::after  { background: #1a73e8; }
        .stat-card.green { background: linear-gradient(135deg,#e6f9f0,#d1fae5); }
        .stat-card.green::after { background: #16a34a; }
        .stat-card.red   { background: linear-gradient(135deg,#fef2f2,#fee2e2); }
        .stat-card.red::after   { background: #dc2626; }

        .stat-icon {
            width:48px; height:48px; border-radius:12px;
            display:inline-flex; align-items:center; justify-content:center;
            font-size:1.25rem; flex-shrink:0;
        }
        .stat-icon.blue  { background:#1a73e8; color:#fff; }
        .stat-icon.green { background:#16a34a; color:#fff; }
        .stat-icon.red   { background:#dc2626; color:#fff; }

        .stat-value { font-size:1.4rem; font-weight:800; line-height:1; color:#1e293b; }
        .stat-label { font-size:.78rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#64748b; margin-top:3px; }

        /* ===== PAGE HEADER ===== */
        .ph-card {
            background:#fff; border:1px solid #e9ecef; border-radius:14px;
            padding:16px 20px; display:flex; align-items:center;
            justify-content:space-between; gap:16px; flex-wrap:wrap;
            margin-bottom:1.25rem; position:relative; overflow:hidden;
            box-shadow:0 1px 6px rgba(0,0,0,.05);
        }
        .ph-card::before {
            content:''; position:absolute; left:0; top:0; bottom:0;
            width:4px; border-radius:14px 0 0 14px;
        }
        .ph-card.index-page::before { background:#1a73e8; }
        .ph-left { display:flex; align-items:center; gap:12px; }
        .ph-icon {
            width:42px; height:42px; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            font-size:1rem; flex-shrink:0;
        }
        .ph-icon.index { background:#e8f0fe; color:#1a73e8; }
        .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
        .ph-breadcrumb {
            display:flex; align-items:center; gap:4px; flex-wrap:wrap;
            margin-top:4px; list-style:none; padding:0; margin-bottom:0;
        }
        .ph-breadcrumb li { display:flex; align-items:center; }
        .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
        .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
        .ph-breadcrumb a:hover { text-decoration:underline; }
        .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

        /* ===== FILTER CARD ===== */
        .filter-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
        .filter-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:18px 24px; }
        .filter-card .card-header h5 { font-size:.95rem; font-weight:700; color:#1e293b; }
        .filter-section { background:#fafbfc; border-bottom:1px solid #f1f5f9; padding:16px 24px; }

        .form-control, .form-select {
            border-radius:10px; border:1.5px solid #e2e8f0; font-size:.83rem;
            padding:7px 12px; color:#334155; background-color:#f8fafc;
            transition:border-color .2s,box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color:#1a73e8; background:#fff; box-shadow:0 0 0 3px rgba(26,115,232,.12);
        }
        .input-group .input-group-text {
            background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none;
            border-radius:10px 0 0 10px; color:#94a3b8; font-size:.8rem;
        }
        .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }

        /* ===== TABLE ===== */
        .table thead th {
            background:#f8fafc; color:#64748b; font-size:.72rem; font-weight:700;
            text-transform:uppercase; letter-spacing:.6px; padding:12px 16px;
            border-bottom:2px solid #e2e8f0; border-top:none; white-space:nowrap;
        }
        .table tbody td { padding:13px 16px; vertical-align:middle; font-size:.85rem; color:#334155; border-bottom:1px solid #f1f5f9; }
        .table tbody tr:last-child td { border-bottom:none; }
        .table-hover tbody tr:hover td { background:#f8fafc; }

        /* ===== BADGES ===== */
        .badge { font-size:.7rem; font-weight:600; padding:4px 9px; border-radius:6px; letter-spacing:.2px; }
        .badge-pemasukan  { background:#dcfce7; color:#15803d; }
        .badge-pengeluaran { background:#fee2e2; color:#dc2626; }
        .badge-donasi { background:#e0f2fe; color:#0369a1; }

        /* ===== ACTION BUTTONS ===== */
        .btn-action {
            width:30px; height:30px; display:inline-flex; align-items:center;
            justify-content:center; border-radius:8px; font-size:.75rem; padding:0;
            border:none; transition:all .15s ease;
        }
        .btn-detail  { background:#e0f2fe; color:#0369a1; }
        .btn-detail:hover { background:#0369a1; color:#fff; }
        .btn-edit    { background:#fef9c3; color:#a16207; }
        .btn-edit:hover   { background:#ca8a04; color:#fff; }
        .btn-hapus   { background:#fee2e2; color:#dc2626; }
        .btn-hapus:hover  { background:#dc2626; color:#fff; }

        /* ===== BTN PRIMARY ===== */
        .btn-primary {
            background:linear-gradient(135deg,#1a73e8,#1558b0); border:none;
            border-radius:10px; font-weight:600; font-size:.83rem; padding:8px 18px;
            box-shadow:0 2px 8px rgba(26,115,232,.35); transition:all .2s ease;
        }
        .btn-primary:hover {
            background:linear-gradient(135deg,#1558b0,#0f3e82);
            box-shadow:0 4px 14px rgba(26,115,232,.45); transform:translateY(-1px);
        }
        .btn-outline-secondary {
            border-radius:10px; font-size:.83rem;
            border-color:#e2e8f0; color:#64748b; padding:7px 12px;
        }
        .btn-outline-secondary:hover { background:#f1f5f9; border-color:#cbd5e1; color:#334155; }

        /* ===== NOMINAL ===== */
        .nominal-pemasukan  { color:#15803d; font-weight:700; }
        .nominal-pengeluaran { color:#dc2626; font-weight:700; }

        /* ===== EMPTY STATE ===== */
        .empty-state { padding:60px 20px; }
        .empty-state-icon {
            width:72px; height:72px; background:#f1f5f9; border-radius:50%;
            display:flex; align-items:center; justify-content:center; margin:0 auto 16px;
        }
        .empty-state-icon i { font-size:1.8rem; color:#94a3b8; }

        /* ===== PRINT MODAL ===== */
        .modal-print .modal-content {
            border:none; border-radius:18px;
            box-shadow:0 20px 60px rgba(0,0,0,.15);
        }
        .modal-print .modal-header {
            background:linear-gradient(135deg,#1a73e8,#1558b0);
            border-radius:18px 18px 0 0; padding:18px 24px; border:none;
        }
        .modal-print .modal-header .modal-title { color:#fff; font-weight:700; font-size:1rem; }
        .modal-print .modal-header .btn-close { filter:invert(1); opacity:.8; }
        .modal-print .modal-body { padding:24px; }

        .periode-tabs { display:flex; gap:6px; margin-bottom:18px; }
        .periode-tab {
            flex:1; padding:9px 0; text-align:center; border-radius:10px;
            border:1.5px solid #e2e8f0; font-size:.82rem; font-weight:600;
            color:#64748b; cursor:pointer; background:#f8fafc;
            transition:all .2s ease;
        }
        .periode-tab:hover  { border-color:#1a73e8; color:#1a73e8; background:#e8f0fe; }
        .periode-tab.active { border-color:#1a73e8; color:#fff; background:linear-gradient(135deg,#1a73e8,#1558b0); }

        .form-label { font-size:.8rem; font-weight:600; color:#475569; margin-bottom:5px; }

        .print-btn {
            background:linear-gradient(135deg,#1a73e8,#1558b0); color:#fff;
            border:none; border-radius:10px; font-weight:600; font-size:.88rem;
            padding:10px 24px; width:100%;
            box-shadow:0 4px 14px rgba(26,115,232,.4); transition:all .2s;
        }
        .print-btn:hover { opacity:.92; transform:translateY(-1px); }

        #section-harian, #section-bulanan, #section-tahunan { display:none; }
        #section-harian.show, #section-bulanan.show, #section-tahunan.show { display:block; }
    </style>
@endsection

@section('content')
    <div class="container">

        {{-- Page Header --}}
        <div class="ph-card index-page">
            <div class="ph-left">
                <div class="ph-icon index"><i class="fas fa-wallet"></i></div>
                <div>
                    <h5 class="ph-title">Data Keuangan</h5>
                    <ol class="ph-breadcrumb" aria-label="breadcrumb">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><span class="bc-active">Keuangan</span></li>
                    </ol>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('keuangan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Transaksi
                </a>
                {{-- Tombol buka modal print --}}
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCetakLaporan">
                    <i class="fas fa-print me-1"></i> Cetak Laporan
                </button>
            </div>
        </div>

        <div class="page-inner">

            {{-- STAT CARDS --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card green">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon green"><i class="fas fa-arrow-circle-down"></i></div>
                            <div>
                                <div class="stat-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                                <div class="stat-label">Total Pemasukan</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card red">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon red"><i class="fas fa-arrow-circle-up"></i></div>
                            <div>
                                <div class="stat-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                                <div class="stat-label">Total Pengeluaran</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card {{ $saldo >= 0 ? 'blue' : 'red' }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon {{ $saldo >= 0 ? 'blue' : 'red' }}"><i class="fas fa-balance-scale"></i></div>
                            <div>
                                <div class="stat-value">Rp {{ number_format(abs($saldo), 0, ',', '.') }}</div>
                                <div class="stat-label">Saldo {{ $saldo < 0 ? '(Defisit)' : '' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Filter & Table Card --}}
            <div class="card filter-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list text-primary me-2"></i>Daftar Transaksi</h5>
                </div>

                {{-- Filter --}}
                <div class="filter-section">
                    <form method="GET" action="{{ route('keuangan.index') }}">
                        <div class="row g-2 align-items-end">

                            {{-- Panti (hanya admin_dinsos) --}}
                            @unless($isAdminPanti)
                                <div class="col-12 col-md-3">
                                    <select name="panti_asuhan_id" class="form-select">
                                        <option value="">Semua Panti</option>
                                        @foreach($pantis as $p)
                                            <option value="{{ $p->id }}" {{ request('panti_asuhan_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_panti }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endunless

                            {{-- Jenis --}}
                            <div class="col-6 col-md-2">
                                <select name="jenis" class="form-select">
                                    <option value="">Semua Jenis</option>
                                    <option value="pemasukan"   {{ request('jenis')==='pemasukan'   ? 'selected' : '' }}>Pemasukan</option>
                                    <option value="pengeluaran" {{ request('jenis')==='pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                                </select>
                            </div>

                            {{-- Bulan --}}
                            <div class="col-6 col-md-2">
                                <select name="bulan" class="form-select">
                                    <option value="">Semua Bulan</option>
                                    @foreach(range(1,12) as $m)
                                        <option value="{{ $m }}" {{ request('bulan')==$m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tahun --}}
                            <div class="col-6 col-md-2">
                                <select name="tahun" class="form-select">
                                    <option value="">Semua Tahun</option>
                                    @foreach(range(date('Y'), date('Y')-5) as $y)
                                        <option value="{{ $y }}" {{ request('tahun')==$y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Search --}}
                            <div class="col-12 col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari keterangan..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('keuangan.index') }}" class="btn btn-outline-secondary btn-sm ms-1" title="Reset">
                                    <i class="fas fa-redo-alt"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-hover mb-0 table">
                            <thead>
                                <tr>
                                    <th width="40">#</th>
                                    <th>Tanggal</th>
                                    @unless($isAdminPanti)
                                        <th>Panti Asuhan</th>
                                    @endunless
                                    <th>Jenis</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Nominal</th>
                                    <th>Sumber</th>
                                    <th width="110">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($keuangans as $i => $item)
                                    <tr>
                                        <td class="text-muted">{{ $keuangans->firstItem() + $i }}</td>
                                        <td>
                                            <div class="fw-semibold" style="font-size:.85rem;">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                            </div>
                                        </td>
                                        @unless($isAdminPanti)
                                            <td>
                                                <div style="font-size:.85rem;color:#1e293b;">
                                                    {{ $item->pantiAsuhan->nama_panti ?? '-' }}
                                                </div>
                                            </td>
                                        @endunless
                                        <td>
                                            <span class="badge badge-{{ $item->jenis }}">{{ ucfirst($item->jenis) }}</span>
                                        </td>
                                        <td>{{ $item->kategori ?? '-' }}</td>
                                        <td>
                                            <div style="max-width:200px;font-size:.83rem;" class="text-truncate" title="{{ $item->keterangan }}">
                                                {{ $item->keterangan ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="nominal-{{ $item->jenis }} text-end">
                                            {{ $item->jenis === 'pengeluaran' ? '- ' : '+ ' }}Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($item->donasi_id)
                                                <span class="badge badge-donasi"><i class="fas fa-hand-holding-heart me-1"></i>Donasi</span>
                                            @else
                                                <span class="text-muted" style="font-size:.78rem;">Manual</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('keuangan.show', $item) }}" class="btn btn-action btn-detail" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('keuangan.edit', $item) }}" class="btn btn-action btn-edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <button class="btn btn-action btn-hapus"
                                                    data-id="{{ $item->id }}"
                                                    data-nominal="Rp {{ number_format($item->nominal, 0, ',', '.') }}"
                                                    title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="form-hapus-{{ $item->id }}" action="{{ route('keuangan.destroy', $item) }}" method="POST" class="d-none">
                                                    @csrf @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="p-0 text-center">
                                            <div class="empty-state">
                                                <div class="empty-state-icon"><i class="fas fa-wallet"></i></div>
                                                <div class="fw-semibold text-secondary mb-1">Belum ada data transaksi</div>
                                                <div class="text-muted" style="font-size:.8rem;">Coba ubah filter atau tambahkan transaksi baru</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @if($keuangans->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            Menampilkan <strong>{{ $keuangans->firstItem() }}</strong>–<strong>{{ $keuangans->lastItem() }}</strong>
                            dari <strong>{{ $keuangans->total() }}</strong> data
                        </small>
                        {{ $keuangans->links() }}
                    </div>
                @endif

            </div>{{-- end .card --}}
        </div>{{-- end .page-inner --}}
    </div>

    {{-- ============================================================ --}}
    {{--                     MODAL CETAK LAPORAN                      --}}
    {{-- ============================================================ --}}
    <div class="modal fade modal-print" id="modalCetakLaporan" tabindex="-1" aria-labelledby="modalCetakLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalCetakLabel">
                        <i class="fas fa-print me-2"></i>Cetak Laporan Keuangan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <form id="formCetakLaporan" action="{{ route('keuangan.laporan.cetak') }}" method="GET" target="_blank">

                        {{-- Pilih Panti — hanya tampil jika admin_dinsos --}}
                        @if(!$isAdminPanti)
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-home me-1 text-primary"></i>Panti Asuhan <span class="text-danger">*</span></label>
                            <select name="panti_asuhan_id" id="modalPantiSelect" class="form-select" required>
                                <option value="">— Pilih Panti —</option>
                                @foreach($pantis as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_panti }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        {{-- Jika admin_panti / pengurus, panti sudah ter-set dari auth --}}
                        <input type="hidden" name="panti_asuhan_id" value="{{ auth()->user()->pengurus->panti_asuhan_id ?? '' }}">
                        @endif

                        {{-- Tipe Periode --}}
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-calendar-alt me-1 text-primary"></i>Periode Laporan</label>
                            <div class="periode-tabs">
                                <div class="periode-tab active" data-periode="harian">Harian</div>
                                <div class="periode-tab"        data-periode="bulanan">Bulanan</div>
                                <div class="periode-tab"        data-periode="tahunan">Tahunan</div>
                            </div>
                            <input type="hidden" name="tipe_periode" id="inputTipePeriode" value="harian">
                        </div>

                        {{-- Section: Harian --}}
                        <div id="section-harian" class="show">
                            <div class="mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        {{-- Section: Bulanan --}}
                        <div id="section-bulanan">
                            <div class="row g-2 mb-3">
                                <div class="col-7">
                                    <label class="form-label">Bulan</label>
                                    <select name="bulan_laporan" class="form-select">
                                        @foreach(range(1,12) as $m)
                                            <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-5">
                                    <label class="form-label">Tahun</label>
                                    <select name="tahun_laporan" class="form-select">
                                        @foreach(range(date('Y'), date('Y')-5) as $y)
                                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Tahunan --}}
                        <div id="section-tahunan">
                            <div class="mb-3">
                                <label class="form-label">Tahun</label>
                                <select name="tahun_laporan_only" class="form-select">
                                    @foreach(range(date('Y'), date('Y')-5) as $y)
                                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="print-btn">
                            <i class="fas fa-file-pdf me-2"></i>Buka & Cetak Laporan
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- ============================================================ --}}

@endsection

@section('scripts')
    <script>
        // ── Tombol hapus ──────────────────────────────────────────────
        document.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', function () {
                const id      = this.dataset.id;
                const nominal = this.dataset.nominal;
                swal({
                    title: 'Hapus Transaksi?',
                    text: `Data transaksi senilai "${nominal}" akan dihapus permanen.`,
                    icon: 'warning',
                    buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus!', className: 'btn-danger' } },
                    dangerMode: true,
                }).then(ok => { if (ok) document.getElementById('form-hapus-' + id).submit(); });
            });
        });

        // ── Periode tabs di modal ─────────────────────────────────────
        const tabs = document.querySelectorAll('.periode-tab');
        const sections = { harian: 'section-harian', bulanan: 'section-bulanan', tahunan: 'section-tahunan' };

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const p = this.dataset.periode;
                document.getElementById('inputTipePeriode').value = p;

                Object.values(sections).forEach(id => document.getElementById(id).classList.remove('show'));
                document.getElementById(sections[p]).classList.add('show');
            });
        });

        // ── Validasi pilih panti (admin_dinsos) sebelum submit ────────
        const formCetak = document.getElementById('formCetakLaporan');
        const pantiSelect = document.getElementById('modalPantiSelect');

        if (formCetak && pantiSelect) {
            formCetak.addEventListener('submit', function (e) {
                if (!pantiSelect.value) {
                    e.preventDefault();
                    pantiSelect.classList.add('is-invalid');
                    pantiSelect.focus();
                } else {
                    pantiSelect.classList.remove('is-invalid');
                }
            });
            pantiSelect.addEventListener('change', function () {
                this.classList.remove('is-invalid');
            });
        }
    </script>
@endsection
