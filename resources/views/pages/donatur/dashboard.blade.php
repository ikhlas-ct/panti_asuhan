@extends('layouts.user.user')
@section('title', 'Dashboard Donatur')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    *, .card, .table, .btn, h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── PAGE HEADER ── */
    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 20px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.25rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:#16a34a; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; background:#f0fdf4; color:#16a34a; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#16a34a; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ── WELCOME BANNER ── */
    .welcome-banner {
        background: linear-gradient(135deg, #16a34a 0%, #0d9488 100%);
        border-radius: 20px; padding: 2rem 2.5rem;
        position: relative; overflow: hidden;
        box-shadow: 0 8px 32px rgba(22,163,74,.25);
        margin-bottom: 1.75rem;
    }
    .welcome-banner::before { content:''; position:absolute; top:-60px; right:-60px; width:260px; height:260px; border-radius:50%; background:rgba(255,255,255,.07); }
    .welcome-banner::after  { content:''; position:absolute; bottom:-80px; left:-40px; width:300px; height:300px; border-radius:50%; background:rgba(255,255,255,.05); }
    .welcome-banner .z1 { position:relative; z-index:1; }

    /* ── AVATAR ── */
    .donatur-avatar-lg {
        width:64px; height:64px; border-radius:16px; flex-shrink:0;
        object-fit:cover; border:3px solid rgba(255,255,255,.5);
    }
    .donatur-avatar-placeholder {
        width:64px; height:64px; border-radius:16px; flex-shrink:0;
        background:rgba(255,255,255,.2); display:flex; align-items:center;
        justify-content:center; font-size:1.6rem; font-weight:800; color:#fff;
        border:3px solid rgba(255,255,255,.4);
    }

    /* ── STAT CARDS ── */
    .stat-card { border:none; border-radius:16px; padding:20px; position:relative; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.07); transition:transform .2s, box-shadow .2s; }
    .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after { content:''; position:absolute; right:-18px; top:-18px; width:80px; height:80px; border-radius:50%; opacity:.12; }
    .sc-blue   { background:linear-gradient(135deg,#e8f0fe,#dbeafe); } .sc-blue::after   { background:#1a73e8; }
    .sc-green  { background:linear-gradient(135deg,#e6f9f0,#d1fae5); } .sc-green::after  { background:#16a34a; }
    .sc-yellow { background:linear-gradient(135deg,#fffbeb,#fef9c3); } .sc-yellow::after { background:#ca8a04; }
    .sc-red    { background:linear-gradient(135deg,#fef2f2,#fee2e2); } .sc-red::after    { background:#dc2626; }
    .sc-teal   { background:linear-gradient(135deg,#f0fdfa,#ccfbf1); } .sc-teal::after   { background:#0d9488; }
    .sc-purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); } .sc-purple::after { background:#7c3aed; }
    .sc-orange { background:linear-gradient(135deg,#fff7ed,#ffedd5); } .sc-orange::after { background:#ea580c; }

    .stat-icon { width:48px; height:48px; border-radius:12px; display:inline-flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
    .si-blue   { background:#1a73e8; color:#fff; } .si-green  { background:#16a34a; color:#fff; }
    .si-yellow { background:#ca8a04; color:#fff; } .si-red    { background:#dc2626; color:#fff; }
    .si-teal   { background:#0d9488; color:#fff; } .si-purple { background:#7c3aed; color:#fff; }
    .si-orange { background:#ea580c; color:#fff; }

    .stat-value { font-size:1.8rem; font-weight:800; line-height:1; color:#1e293b; }
    .stat-value.sm { font-size:1.15rem; }
    .stat-label { font-size:.73rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#64748b; margin-top:4px; }
    .stat-sub   { font-size:.73rem; color:#94a3b8; margin-top:2px; }

    /* ── DASH CARDS ── */
    .dash-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
    .dash-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:16px 22px; }
    .dash-card .card-body { padding:22px; }
    .dash-card .card-footer { background:#f8fafc; border-top:1px solid #f1f5f9; padding:12px 22px; }

    /* ── TABLE ── */
    .dash-table thead th { background:#f8fafc; color:#64748b; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; padding:11px 16px; border-bottom:2px solid #e2e8f0; border-top:none; white-space:nowrap; }
    .dash-table tbody td { padding:12px 16px; vertical-align:middle; font-size:.84rem; color:#334155; border-bottom:1px solid #f1f5f9; }
    .dash-table tbody tr:last-child td { border-bottom:none; }
    .dash-table tbody tr:hover td { background:#f8fafc; }

    /* ── BADGES ── */
    .badge { font-size:.68rem; font-weight:600; padding:4px 9px; border-radius:6px; }
    .bdg-pending  { background:#fffbeb; color:#92400e; }
    .bdg-diterima { background:#dcfce7; color:#15803d; }
    .bdg-ditolak  { background:#fee2e2; color:#dc2626; }
    .bdg-uang     { background:#e0f2fe; color:#0369a1; }
    .bdg-barang   { background:#f0fdf4; color:#15803d; }
    .bdg-online   { background:#f5f3ff; color:#7c3aed; }
    .bdg-kunjungan{ background:#fff7ed; color:#c2410c; }

    /* ── QUICK LINK ── */
    .quick-link { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:18px 10px; border-radius:14px; text-decoration:none; transition:all .2s; gap:8px; }
    .quick-link:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,0,0,.12); }
    .quick-link i { font-size:1.35rem; }
    .quick-link span { font-size:.75rem; font-weight:700; }
    .ql-green  { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; }
    .ql-blue   { background:linear-gradient(135deg,#1a73e8,#1558b0); color:#fff; }
    .ql-teal   { background:linear-gradient(135deg,#0d9488,#0f766e); color:#fff; }
    .ql-orange { background:linear-gradient(135deg,#ea580c,#c2410c); color:#fff; }
    .ql-purple { background:linear-gradient(135deg,#7c3aed,#6d28d9); color:#fff; }

    /* ── PANTI LIST ── */
    .panti-item { display:flex; align-items:center; gap:12px; padding:12px 0; border-bottom:1px solid #f1f5f9; }
    .panti-item:last-child { border-bottom:none; padding-bottom:0; }
    .panti-rank { width:28px; height:28px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; flex-shrink:0; }
    .rank-1 { background:#fef9c3; color:#ca8a04; } .rank-2 { background:#f1f5f9; color:#64748b; }
    .rank-3 { background:#fff7ed; color:#c2410c; } .rank-n { background:#f8fafc; color:#94a3b8; }

    /* ── PENDING ALERT ── */
    .pending-alert { background:linear-gradient(135deg,#fffbeb,#fef9c3); border:1.5px solid #fde68a; border-radius:14px; padding:16px 20px; }

    /* ── PROFIL INFO CARD ── */
    .profil-info-item { display:flex; align-items:flex-start; gap:10px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
    .profil-info-item:last-child { border-bottom:none; }
    .profil-info-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.78rem; flex-shrink:0; }

    .btn-sm-link { font-size:.75rem; color:#16a34a; text-decoration:none; font-weight:600; }
    .btn-sm-link:hover { text-decoration:underline; }
    .empty-sm { text-align:center; padding:28px 16px; color:#94a3b8; font-size:.82rem; }

    .sec-title { font-size:.92rem; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px; }
    .sec-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }

    .alert { border-radius:12px; border:none; font-size:.85rem; padding:12px 18px; }
    .alert-success { background:#dcfce7; color:#15803d; }
    .alert-danger  { background:#fee2e2; color:#991b1b; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- ── PAGE HEADER ── --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-tachometer-alt"></i></div>
            <div>
                <h5 class="ph-title">Dashboard Donatur</h5>
                <ol class="ph-breadcrumb">
                    <li><span class="bc-active">Dashboard</span></li>
                </ol>
            </div>
        </div>
        <div style="font-size:.8rem;color:#94a3b8;">
            <i class="fas fa-calendar me-1"></i>{{ now()->translatedFormat('d F Y') }}
            &nbsp;&bull;&nbsp;
            <i class="fas fa-clock me-1"></i><span id="jam-realtime"></span>
        </div>
    </div>

    <div class="page-inner">

        {{-- Flash --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                <i class="fas fa-check-circle"></i>{{ session('success') }}
            </div>
        @endif

        {{-- ── WELCOME BANNER ── --}}
        <div class="welcome-banner mb-4">
            <div class="z1 d-flex align-items-center gap-4 flex-wrap">
                {{-- Avatar --}}
                @if($donatur->foto && file_exists(storage_path('app/public/'.$donatur->foto)))
                    <img src="{{ asset('storage/'.$donatur->foto) }}"
                         alt="{{ $donatur->nama }}" class="donatur-avatar-lg">
                @else
                    <div class="donatur-avatar-placeholder">
                        {{ strtoupper(substr($donatur->nama, 0, 1)) }}
                    </div>
                @endif

                <div class="text-white flex-grow-1">
                    <div style="font-size:.82rem;opacity:.8;margin-bottom:3px;">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                    <h4 class="fw-bold mb-1">Halo, {{ $donatur->nama }}! 👋</h4>
                    <div class="d-flex align-items-center gap-3 flex-wrap" style="font-size:.83rem;opacity:.85;">
                        <span><i class="fas fa-tag me-1"></i>{{ ucfirst($donatur->jenis_donatur) }}</span>
                        @if($donatur->no_telp)
                            <span><i class="fas fa-phone me-1"></i>{{ $donatur->no_telp }}</span>
                        @endif
                        <span class="badge {{ $donatur->status==='aktif'?'bg-success':'bg-secondary' }}"
                              style="font-size:.72rem;">
                            {{ ucfirst($donatur->status) }}
                        </span>
                    </div>
                </div>

                <div class="text-white text-end" style="font-size:.82rem;opacity:.8;">
                    <div><i class="fas fa-hand-holding-heart me-1"></i>{{ $stats['total'] }} total donasi</div>
                    <div class="mt-1"><i class="fas fa-check-circle me-1"></i>{{ $stats['diterima'] }} diterima</div>
                </div>
            </div>
        </div>

        {{-- ── ALERT DONASI PENDING ── --}}
        @if($stats['pending'] > 0)
        <div class="pending-alert d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div style="width:40px;height:40px;background:#fde68a;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-clock" style="color:#92400e;font-size:1rem;"></i>
                </div>
                <div>
                    <div class="fw-bold" style="color:#92400e;font-size:.9rem;">
                        <strong>{{ $stats['pending'] }} donasi</strong> Anda sedang menunggu verifikasi
                    </div>
                    <div style="font-size:.77rem;color:#a16207;">Admin akan segera memverifikasi donasi Anda.</div>
                </div>
            </div>
            <a href="{{ route('donasi.index', ['status'=>'pending']) }}"
               class="btn btn-sm px-4"
               style="background:#ca8a04;color:#fff;border-radius:10px;font-size:.82rem;font-weight:600;border:none;">
                <i class="fas fa-arrow-right me-1"></i> Lihat
            </a>
        </div>
        @endif

        {{-- ── STAT CARDS ── --}}
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-4 col-xl">
                <div class="card stat-card sc-blue">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon si-blue"><i class="fas fa-hand-holding-heart"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['total'] }}</div>
                            <div class="stat-label">Total Donasi</div>
                            <div class="stat-sub">Semua status</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl">
                <div class="card stat-card sc-green">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['diterima'] }}</div>
                            <div class="stat-label">Diterima</div>
                            <div class="stat-sub">Sudah diverifikasi</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl">
                <div class="card stat-card sc-yellow">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon si-yellow"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['pending'] }}</div>
                            <div class="stat-label">Pending</div>
                            <div class="stat-sub">Menunggu verifikasi</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl">
                <div class="card stat-card sc-red">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon si-red"><i class="fas fa-times-circle"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['ditolak'] }}</div>
                            <div class="stat-label">Ditolak</div>
                            <div class="stat-sub">Tidak disetujui</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-xl">
                <div class="card stat-card sc-teal">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon si-teal"><i class="fas fa-coins"></i></div>
                        <div>
                            <div class="stat-value sm">
                                Rp {{ number_format($stats['total_uang']/1000000, 1) }}Jt
                            </div>
                            <div class="stat-label">Uang Disumbang</div>
                            <div class="stat-sub">Yang sudah diterima</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── STAT ROW 2 ── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card stat-card sc-purple">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon si-purple"><i class="fas fa-money-bill-wave"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['distribusi_uang'] ?? \App\Models\Donasi::where('donatur_id',$donatur->id)->where('jenis_donasi','uang')->count() }}</div>
                            <div class="stat-label">Donasi Uang</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card sc-green">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon si-green"><i class="fas fa-box-open"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['total_barang'] }}</div>
                            <div class="stat-label">Donasi Barang</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card sc-orange">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon si-orange"><i class="fas fa-wifi"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['total_online'] }}</div>
                            <div class="stat-label">Metode Online</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card stat-card sc-teal">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon si-teal"><i class="fas fa-walking"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['total_kunjungan'] }}</div>
                            <div class="stat-label">Metode Kunjungan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── QUICK LINKS ── --}}
        <div class="card dash-card mb-4">
            <div class="card-header">
                <div class="sec-title"><span class="sec-dot" style="background:#16a34a;"></span>Akses Cepat</div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-4 col-lg-2-4">
                        <a href="{{ route('donasi.create') }}" class="quick-link ql-green">
                            <i class="fas fa-plus-circle"></i><span>Donasi Sekarang</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2-4">
                        <a href="{{ route('donasi.index') }}" class="quick-link ql-blue">
                            <i class="fas fa-list"></i><span>Riwayat Donasi</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2-4">
                        <a href="{{ route('donasi.index', ['status'=>'pending']) }}" class="quick-link ql-orange">
                            <i class="fas fa-clock"></i><span>Cek Status</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2-4">
                        <a href="{{ route('donatur.profil') }}" class="quick-link ql-teal">
                            <i class="fas fa-user-circle"></i><span>Profil Saya</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2-4">
                        <a href="{{ route('donasi.index', ['status'=>'diterima']) }}" class="quick-link ql-purple">
                            <i class="fas fa-check-double"></i><span>Donasi Diterima</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── GRAFIK + PIE ── --}}
        <div class="row g-4 mb-4">

            {{-- Grafik Bulanan --}}
            <div class="col-lg-8">
                <div class="card dash-card h-100">
                    <div class="card-header">
                        <div class="sec-title">
                            <span class="sec-dot" style="background:#16a34a;"></span>
                            Riwayat Donasi per Bulan (12 Bulan Terakhir)
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartDonasiBulanan" height="120"></canvas>
                    </div>
                </div>
            </div>

            {{-- Pie Charts --}}
            <div class="col-lg-4">
                <div class="card dash-card mb-4">
                    <div class="card-header">
                        <div class="sec-title"><span class="sec-dot" style="background:#7c3aed;"></span>Jenis Donasi</div>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chartJenis" height="180"></canvas>
                    </div>
                </div>
                <div class="card dash-card">
                    <div class="card-header">
                        <div class="sec-title"><span class="sec-dot" style="background:#ea580c;"></span>Status Donasi</div>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chartStatus" height="180"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── RIWAYAT DONASI + DONASI PENDING ── --}}
        <div class="row g-4 mb-4">

            {{-- Riwayat Terbaru --}}
            <div class="col-lg-7">
                <div class="card dash-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="sec-title"><span class="sec-dot" style="background:#16a34a;"></span>Donasi Terbaru</div>
                        <a href="{{ route('donasi.index') }}" class="btn-sm-link">Lihat Semua →</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table dash-table mb-0">
                                <thead><tr>
                                    <th>Panti Tujuan</th>
                                    <th>Jenis</th>
                                    <th>Nominal / Barang</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr></thead>
                                <tbody>
                                @forelse($donasi_terbaru as $d)
                                <tr style="cursor:pointer;" onclick="location.href='{{ route('donasi.show',$d) }}'">
                                    <td>
                                        <div class="fw-semibold" style="color:#1e293b;font-size:.84rem;">{{ $d->pantiAsuhan->nama_panti ?? '-' }}</div>
                                        <div style="font-size:.71rem;color:#94a3b8;">
                                            <span class="badge bdg-{{ $d->metode }}" style="font-size:.66rem;">{{ ucfirst($d->metode) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bdg-{{ $d->jenis_donasi }}">
                                            <i class="fas fa-{{ $d->jenis_donasi==='uang'?'money-bill':'box' }} me-1"></i>
                                            {{ ucfirst($d->jenis_donasi) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($d->jenis_donasi==='uang')
                                            <span class="fw-semibold text-success" style="font-size:.83rem;">
                                                Rp {{ number_format($d->nominal,0,',','.') }}
                                            </span>
                                        @else
                                            <span style="font-size:.82rem;">{{ $d->barang->count() }} jenis</span>
                                        @endif
                                    </td>
                                    <td style="font-size:.8rem;color:#64748b;">{{ $d->tanggal_donasi?->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bdg-{{ $d->status }}">{{ ucfirst($d->status) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5">
                                    <div class="empty-sm">
                                        <i class="fas fa-hand-holding-heart mb-2" style="display:block;font-size:1.5rem;"></i>
                                        Belum ada donasi. Yuk mulai berdonasi!
                                    </div>
                                </td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kanan: Pending + Profil Info --}}
            <div class="col-lg-5">

                {{-- Donasi Pending --}}
                <div class="card dash-card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="sec-title">
                            <span class="sec-dot" style="background:#ca8a04;"></span>
                            Menunggu Verifikasi
                            @if($stats['pending']>0)
                                <span class="badge" style="background:#ca8a04;color:#fff;font-size:.67rem;">{{ $stats['pending'] }}</span>
                            @endif
                        </div>
                        <a href="{{ route('donasi.index',['status'=>'pending']) }}" class="btn-sm-link">Semua →</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table dash-table mb-0">
                                <thead><tr>
                                    <th>Panti</th>
                                    <th>Jenis</th>
                                    <th>Tgl</th>
                                    <th width="60">Aksi</th>
                                </tr></thead>
                                <tbody>
                                @forelse($donasi_pending as $d)
                                <tr>
                                    <td style="font-size:.83rem;font-weight:600;color:#1e293b;">
                                        {{ $d->pantiAsuhan->nama_panti ?? '-' }}
                                    </td>
                                    <td><span class="badge bdg-{{ $d->jenis_donasi }}">{{ ucfirst($d->jenis_donasi) }}</span></td>
                                    <td style="font-size:.78rem;color:#64748b;">{{ $d->tanggal_donasi?->format('d/m') }}</td>
                                    <td>
                                        <a href="{{ route('donasi.show',$d) }}"
                                           class="btn btn-sm px-2 py-1"
                                           style="background:#e0f2fe;color:#0369a1;border-radius:8px;font-size:.72rem;font-weight:600;border:none;">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4">
                                    <div class="empty-sm">
                                        <i class="fas fa-check-circle mb-2" style="display:block;font-size:1.4rem;color:#16a34a;"></i>
                                        Tidak ada donasi pending!
                                    </div>
                                </td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Profil Info --}}
                <div class="card dash-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="sec-title"><span class="sec-dot" style="background:#16a34a;"></span>Profil Saya</div>
                        <a href="{{ route('donatur.profil') }}" class="btn-sm-link">Edit →</a>
                    </div>
                    <div class="card-body">
                        <div class="profil-info-item">
                            <div class="profil-info-icon" style="background:#f0fdf4;"><i class="fas fa-user text-success" style="font-size:.8rem;"></i></div>
                            <div>
                                <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;letter-spacing:.04em;">Nama</div>
                                <div style="font-size:.88rem;font-weight:600;color:#1e293b;">{{ $donatur->nama }}</div>
                            </div>
                        </div>
                        <div class="profil-info-item">
                            <div class="profil-info-icon" style="background:#eff6ff;"><i class="fas fa-tag" style="color:#1a73e8;font-size:.8rem;"></i></div>
                            <div>
                                <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;letter-spacing:.04em;">Jenis</div>
                                <div style="font-size:.88rem;font-weight:600;color:#1e293b;">{{ ucfirst($donatur->jenis_donatur) }}</div>
                            </div>
                        </div>
                        @if($donatur->no_telp)
                        <div class="profil-info-item">
                            <div class="profil-info-icon" style="background:#f0fdf4;"><i class="fab fa-whatsapp text-success" style="font-size:.8rem;"></i></div>
                            <div>
                                <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;letter-spacing:.04em;">No. HP</div>
                                <div style="font-size:.88rem;font-weight:600;color:#1e293b;">{{ $donatur->no_telp }}</div>
                            </div>
                        </div>
                        @endif
                        @if($donatur->alamat)
                        <div class="profil-info-item">
                            <div class="profil-info-icon" style="background:#fff7ed;"><i class="fas fa-map-marker-alt" style="color:#ea580c;font-size:.8rem;"></i></div>
                            <div>
                                <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;letter-spacing:.04em;">Alamat</div>
                                <div style="font-size:.86rem;color:#1e293b;">{{ Str::limit($donatur->alamat, 50) }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="profil-info-item">
                            <div class="profil-info-icon" style="background:#f8f9fa;"><i class="fas fa-calendar text-secondary" style="font-size:.8rem;"></i></div>
                            <div>
                                <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;letter-spacing:.04em;">Bergabung</div>
                                <div style="font-size:.86rem;color:#1e293b;">{{ $donatur->created_at?->translatedFormat('d F Y') ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── PANTI YANG PERNAH DIBANTU ── --}}
        @if($panti_list->count() > 0)
        <div class="card dash-card mb-4">
            <div class="card-header">
                <div class="sec-title"><span class="sec-dot" style="background:#0d9488;"></span>Panti Asuhan yang Pernah Dibantu</div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($panti_list as $i => $item)
                    <div class="col-12 col-md-6">
                        <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:12px;">
                            <div class="panti-rank {{ $i===0?'rank-1':($i===1?'rank-2':($i===2?'rank-3':'rank-n')) }}">
                                {{ $i+1 }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div class="fw-semibold" style="font-size:.85rem;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $item->pantiAsuhan->nama_panti ?? '-' }}
                                </div>
                                <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">
                                    {{ $item->total_donasi }} kali donasi
                                    @if($item->total_uang > 0)
                                        &bull; Rp {{ number_format($item->total_uang/1000000,1) }}Jt
                                    @endif
                                </div>
                            </div>
                            <span class="badge bdg-diterima">Diterima</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>{{-- end .page-inner --}}
</div>{{-- end .container --}}
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    // Jam realtime
    function updateJam() {
        const now = new Date();
        document.getElementById('jam-realtime').textContent =
            now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' }) + ' WIB';
    }
    updateJam(); setInterval(updateJam, 1000);

    const donasiPerBulan   = @json($donasi_per_bulan);
    const distribusiJenis  = @json($distribusi_jenis);
    const distribusiStatus = @json($distribusi_status);

    // ── Chart 1: Donasi Bulanan ───────────────────────────────────
    (function() {
        const labels = [], dataTotalDonasi = [], dataNominalUang = [];
        for (let i = 11; i >= 0; i--) {
            const d = new Date();
            d.setMonth(d.getMonth() - i);
            const bln = d.getMonth() + 1, thn = d.getFullYear();
            labels.push(d.toLocaleString('id-ID', { month:'short', year:'2-digit' }));
            const found = donasiPerBulan.find(x => x.bulan == bln && x.tahun == thn);
            dataTotalDonasi.push(found ? found.total : 0);
            dataNominalUang.push(found ? parseFloat(found.total_uang) / 1000000 : 0);
        }
        new Chart(document.getElementById('chartDonasiBulanan'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Jumlah Donasi',
                        data: dataTotalDonasi,
                        backgroundColor: 'rgba(22,163,74,.15)',
                        borderColor: '#16a34a',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y1'
                    },
                    {
                        label: 'Nominal Uang (Juta)',
                        data: dataNominalUang,
                        type: 'line',
                        borderColor: '#0d9488',
                        backgroundColor: 'rgba(13,148,136,.08)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#0d9488',
                        pointRadius: 4,
                        yAxisID: 'y2'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode:'index', intersect:false },
                plugins: { legend:{ position:'top', labels:{ font:{ family:'Plus Jakarta Sans', size:11 }, padding:16 } } },
                scales: {
                    y1: { type:'linear', position:'left',  beginAtZero:true, ticks:{ stepSize:1, font:{size:10} }, grid:{ color:'#f1f5f9' } },
                    y2: { type:'linear', position:'right', beginAtZero:true, ticks:{ callback:v=>'Rp'+v+'Jt', font:{size:10} }, grid:{ drawOnChartArea:false } },
                    x:  { ticks:{ font:{size:10} }, grid:{ color:'#f8fafc' } }
                }
            }
        });
    })();

    // ── Chart 2: Jenis Donasi ─────────────────────────────────────
    new Chart(document.getElementById('chartJenis'), {
        type: 'doughnut',
        data: {
            labels: ['Uang', 'Barang'],
            datasets: [{
                data: [distribusiJenis.uang || 0, distribusiJenis.barang || 0],
                backgroundColor: ['#1a73e8', '#16a34a'],
                borderWidth: 3, borderColor: '#fff', hoverOffset: 6
            }]
        },
        options: {
            responsive: true, cutout: '65%',
            plugins: { legend:{ position:'bottom', labels:{ font:{ family:'Plus Jakarta Sans', size:11 }, padding:12 } } }
        }
    });

    // ── Chart 3: Status Donasi ────────────────────────────────────
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Diterima', 'Pending', 'Ditolak'],
            datasets: [{
                data: [distribusiStatus.diterima || 0, distribusiStatus.pending || 0, distribusiStatus.ditolak || 0],
                backgroundColor: ['#16a34a', '#ca8a04', '#dc2626'],
                borderWidth: 3, borderColor: '#fff', hoverOffset: 6
            }]
        },
        options: {
            responsive: true, cutout: '65%',
            plugins: { legend:{ position:'bottom', labels:{ font:{ family:'Plus Jakarta Sans', size:11 }, padding:12 } } }
        }
    });
</script>
@endsection
