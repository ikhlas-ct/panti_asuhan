@extends('layouts.user.user')
@section('title', 'Data Donasi')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    *, .card, .table, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── STAT CARDS ── */
    .stat-card { border:none; border-radius:16px; padding:20px; position:relative; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.07); transition:transform .2s, box-shadow .2s; }
    .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.12); }
    .stat-card::after { content:''; position:absolute; right:-18px; top:-18px; width:80px; height:80px; border-radius:50%; opacity:.12; }
    .stat-card.blue   { background:linear-gradient(135deg,#e8f0fe,#dbeafe); } .stat-card.blue::after   { background:#1a73e8; }
    .stat-card.yellow { background:linear-gradient(135deg,#fffbeb,#fef9c3); } .stat-card.yellow::after { background:#ca8a04; }
    .stat-card.green  { background:linear-gradient(135deg,#e6f9f0,#d1fae5); } .stat-card.green::after  { background:#16a34a; }
    .stat-card.red    { background:linear-gradient(135deg,#fef2f2,#fee2e2); } .stat-card.red::after    { background:#dc2626; }
    .stat-card.purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); } .stat-card.purple::after { background:#7c3aed; }
    .stat-icon { width:46px; height:46px; border-radius:12px; display:inline-flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0; }
    .stat-icon.blue{background:#1a73e8;color:#fff;} .stat-icon.yellow{background:#ca8a04;color:#fff;}
    .stat-icon.green{background:#16a34a;color:#fff;} .stat-icon.red{background:#dc2626;color:#fff;} .stat-icon.purple{background:#7c3aed;color:#fff;}
    .stat-value { font-size:1.8rem; font-weight:800; line-height:1; color:#1e293b; }
    .stat-label { font-size:.73rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#64748b; margin-top:4px; }

    /* ── PAGE HEADER ── */
    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:18px 24px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.5rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:#1a73e8; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; background:#e8f0fe; color:#1a73e8; }
    .ph-title { font-size:1.1rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ── TABLE CARD ── */
    .table-card { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
    .table-card .card-header { background:#fff; border-bottom:1px solid #f1f5f9; padding:18px 24px; }
    .filter-section { background:#fafbfc; border-bottom:1px solid #f1f5f9; padding:14px 24px; }
    .form-control,.form-select { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.83rem; padding:8px 12px; background:#f8fafc; transition:border-color .2s,box-shadow .2s; }
    .form-control:focus,.form-select:focus { border-color:#1a73e8; background:#fff; box-shadow:0 0 0 3px rgba(26,115,232,.12); }
    .input-group .input-group-text { background:#f8fafc; border:1.5px solid #e2e8f0; border-right:none; border-radius:10px 0 0 10px; color:#94a3b8; }
    .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }

    .table thead th { background:#f8fafc; color:#64748b; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; padding:12px 16px; border-bottom:2px solid #e2e8f0; border-top:none; white-space:nowrap; }
    .table tbody td { padding:12px 16px; vertical-align:middle; font-size:.85rem; border-bottom:1px solid #f1f5f9; }
    .table tbody tr:last-child td { border-bottom:none; }
    .table-hover tbody tr:hover td { background:#f8fafc; }

    /* ── BADGES ── */
    .badge { font-size:.69rem; font-weight:600; padding:4px 9px; border-radius:6px; }
    .badge-pending  { background:#fffbeb; color:#92400e; }
    .badge-diterima { background:#dcfce7; color:#15803d; }
    .badge-ditolak  { background:#fee2e2; color:#dc2626; }
    .badge-uang     { background:#e0f2fe; color:#0369a1; }
    .badge-barang   { background:#f0fdf4; color:#15803d; }
    .badge-online   { background:#f5f3ff; color:#7c3aed; }
    .badge-kunjungan{ background:#fff7ed; color:#c2410c; }
    .badge-auto     { background:#dcfce7; color:#15803d; font-size:.67rem; }

    .btn-action { width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; font-size:.75rem; padding:0; border:none; transition:all .15s; }
    .btn-detail { background:#e0f2fe; color:#0369a1; } .btn-detail:hover { background:#0369a1; color:#fff; }
    .btn-edit   { background:#fef9c3; color:#a16207; } .btn-edit:hover   { background:#ca8a04; color:#fff; }
    .btn-hapus  { background:#fee2e2; color:#dc2626; } .btn-hapus:hover  { background:#dc2626; color:#fff; }

    .btn-primary { background:linear-gradient(135deg,#1a73e8,#1558b0); border:none; border-radius:10px; font-weight:600; font-size:.83rem; padding:8px 18px; box-shadow:0 2px 8px rgba(26,115,232,.3); transition:all .2s; }
    .btn-primary:hover { transform:translateY(-1px); }
    .btn-outline-secondary { border-radius:10px; font-size:.83rem; border-color:#e2e8f0; color:#64748b; padding:7px 12px; }
    .card-footer { background:#f8fafc; border-top:1px solid #f1f5f9; padding:12px 24px; }
    .alert { border-radius:12px; border:none; font-size:.85rem; padding:12px 18px; }
    .alert-success { background:#dcfce7; color:#15803d; }
    .alert-danger  { background:#fee2e2; color:#991b1b; }
    .role-banner { border-radius:12px; padding:12px 18px; margin-bottom:1.25rem; font-size:.83rem; display:flex; align-items:center; gap:10px; }
    .role-banner.donatur { background:#fffbeb; border:1px solid #fde68a; color:#92400e; }
    .role-banner.verifier { background:#e0f2fe; border:1px solid #bae6fd; color:#075985; }
    .empty-icon { width:64px; height:64px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 12px; }
</style>
@endsection

@section('content')
<div class="container">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-hand-holding-heart"></i></div>
            <div>
                <h5 class="ph-title">Data Donasi</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><span class="bc-active">Donasi</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('donasi.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Donasi
        </a>
            <a href="{{ route('laporan.donasi-per-panti') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Laporan Donasi per Panti
        </a>
    </div>

    {{-- Role Banner --}}
    @if(auth()->user()->role === 'donatur')
        <div class="role-banner donatur">
            <i class="fas fa-info-circle"></i>
            <span>Donasi Anda akan berstatus <strong>Pending</strong> sampai diverifikasi oleh Admin Dinsos atau Admin Panti.</span>
        </div>
    @elseif(in_array(auth()->user()->role, ['admin_dinsos','admin_panti']))
        <div class="role-banner verifier">
            <i class="fas fa-shield-alt"></i>
            <span>Sebagai <strong>{{ auth()->user()->role === 'admin_dinsos' ? 'Admin Dinsos' : 'Admin Panti' }}</strong>,
            donasi yang Anda tambahkan <strong>langsung diterima</strong>. Donasi uang otomatis tercatat ke keuangan.</span>
        </div>
    @endif

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert alert-success d-flex gap-2 align-items-center mb-4"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex gap-2 align-items-center mb-4"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md">
            <div class="card stat-card blue"><div class="d-flex align-items-center gap-3">
                <div class="stat-icon blue"><i class="fas fa-list"></i></div>
                <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total</div></div>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card stat-card yellow"><div class="d-flex align-items-center gap-3">
                <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
                <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Pending</div></div>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card stat-card green"><div class="d-flex align-items-center gap-3">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div><div class="stat-value">{{ $stats['diterima'] }}</div><div class="stat-label">Diterima</div></div>
            </div></div>
        </div>
        <div class="col-6 col-md">
            <div class="card stat-card red"><div class="d-flex align-items-center gap-3">
                <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
                <div><div class="stat-value">{{ $stats['ditolak'] }}</div><div class="stat-label">Ditolak</div></div>
            </div></div>
        </div>
        <div class="col-12 col-md">
            <div class="card stat-card purple"><div class="d-flex align-items-center gap-3">
                <div class="stat-icon purple"><i class="fas fa-coins"></i></div>
                <div>
                    <div class="stat-value" style="font-size:1.05rem;">Rp {{ number_format($stats['nominal'],0,',','.') }}</div>
                    <div class="stat-label">Uang Masuk</div>
                </div>
            </div></div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold" style="font-size:.95rem;color:#1e293b;">
                <i class="fas fa-list text-primary me-2 opacity-75"></i>Daftar Donasi
            </h5>
            <span class="text-muted" style="font-size:.78rem;">{{ $donasis->total() }} data</span>
        </div>

        <div class="filter-section">
            <form method="GET" action="{{ route('donasi.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari donatur..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select name="jenis_donasi" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="uang"   {{ request('jenis_donasi')==='uang'  ?'selected':'' }}>Uang</option>
                        <option value="barang" {{ request('jenis_donasi')==='barang'?'selected':'' }}>Barang</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="metode" class="form-select">
                        <option value="">Semua Metode</option>
                        <option value="online"    {{ request('metode')==='online'   ?'selected':'' }}>Online</option>
                        <option value="kunjungan" {{ request('metode')==='kunjungan'?'selected':'' }}>Kunjungan</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending"  {{ request('status')==='pending' ?'selected':'' }}>Pending</option>
                        <option value="diterima" {{ request('status')==='diterima'?'selected':'' }}>Diterima</option>
                        <option value="ditolak"  {{ request('status')==='ditolak' ?'selected':'' }}>Ditolak</option>
                    </select>
                </div>
                @if(auth()->user()->role === 'admin_dinsos')
                <div class="col-md-2">
                    <select name="panti_asuhan_id" class="form-select">
                        <option value="">Semua Panti</option>
                        @foreach($pantis as $p)
                            <option value="{{ $p->id }}" {{ request('panti_asuhan_id')==$p->id?'selected':'' }}>{{ $p->nama_panti }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i>Filter</button>
                    @if(request()->hasAny(['search','jenis_donasi','status','metode','panti_asuhan_id']))
                        <a href="{{ route('donasi.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th width="40">#</th>
                        <th>Donatur</th>
                        @if(auth()->user()->role !== 'donatur')<th>Panti Tujuan</th>@endif
                        <th>Jenis</th>
                        <th>Metode</th>
                        <th>Nominal / Barang</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th width="100">Aksi</th>
                    </tr></thead>
                    <tbody>
                    @forelse($donasis as $i => $donasi)
                    <tr>
                        <td class="text-muted">{{ $donasis->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold" style="color:#1e293b;">{{ $donasi->donatur->nama ?? '-' }}</div>
                            <div style="font-size:.73rem;color:#94a3b8;">{{ ucfirst($donasi->donatur->jenis_donatur ?? '') }}</div>
                        </td>
                        @if(auth()->user()->role !== 'donatur')
                        <td style="font-size:.84rem;">{{ $donasi->pantiAsuhan->nama_panti ?? '-' }}</td>
                        @endif
                        <td>
                            <span class="badge badge-{{ $donasi->jenis_donasi }}">
                                <i class="fas fa-{{ $donasi->jenis_donasi==='uang'?'money-bill':'box' }} me-1"></i>
                                {{ ucfirst($donasi->jenis_donasi) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $donasi->metode }}">
                                <i class="fas fa-{{ $donasi->metode==='online'?'wifi':'walking' }} me-1"></i>
                                {{ ucfirst($donasi->metode) }}
                            </span>
                        </td>
                        <td>
                            @if($donasi->jenis_donasi === 'uang')
                                <div class="fw-semibold text-success">Rp {{ number_format($donasi->nominal,0,',','.') }}</div>
                            @else
                                <div class="fw-semibold">{{ $donasi->barang->count() }} jenis barang</div>
                                <div style="font-size:.73rem;color:#94a3b8;">{{ $donasi->totalItemBarang() }} total item</div>
                            @endif
                        </td>
                        <td style="font-size:.83rem;">{{ $donasi->tanggal_donasi?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $donasi->status }}">
                                @if($donasi->status==='pending')     <i class="fas fa-clock me-1"></i>
                                @elseif($donasi->status==='diterima') <i class="fas fa-check me-1"></i>
                                @else                                 <i class="fas fa-times me-1"></i>
                                @endif
                                {{ ucfirst($donasi->status) }}
                            </span>
                            {{-- Label auto jika admin yang input --}}
                            @if($donasi->status==='diterima' && $donasi->dikonfirmasiOleh && in_array($donasi->dikonfirmasiOleh->role,['admin_dinsos','admin_panti']))
                                <div><span class="badge badge-auto"><i class="fas fa-bolt me-1"></i>Auto</span></div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('donasi.show',$donasi) }}" class="btn btn-action btn-detail" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$donasi->sudahDikonfirmasi())
                                    <a href="{{ route('donasi.edit',$donasi) }}" class="btn btn-action btn-edit" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <button class="btn btn-action btn-hapus"
                                        data-id="{{ $donasi->id }}" data-nama="{{ $donasi->donatur->nama ?? '' }}" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="form-hapus-{{ $donasi->id }}" action="{{ route('donasi.destroy',$donasi) }}" method="POST" class="d-none">
                                        @csrf @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-5">
                        <div class="empty-icon"><i class="fas fa-hand-holding-heart" style="font-size:1.6rem;color:#94a3b8;"></i></div>
                        <div class="fw-semibold text-secondary mb-1">Belum ada data donasi</div>
                        <div class="text-muted" style="font-size:.8rem;">Ubah filter atau tambahkan data baru</div>
                    </td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($donasis->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">Menampilkan <strong>{{ $donasis->firstItem() }}</strong>–<strong>{{ $donasis->lastItem() }}</strong> dari <strong>{{ $donasis->total() }}</strong></small>
            {{ $donasis->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', function() {
        swal({ title:'Hapus Donasi?', text:`Data donasi dari "${this.dataset.nama}" akan dihapus.`,
            icon:'warning', buttons:{cancel:'Batal',confirm:{text:'Hapus!',className:'btn-danger'}}, dangerMode:true,
        }).then(ok => { if (ok) document.getElementById('form-hapus-' + this.dataset.id).submit(); });
    });
});
</script>
@endsection
