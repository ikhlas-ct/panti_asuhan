@extends('layouts.user.user')

@section('title', 'Data Pengurus')

@section('styles')
<style>
    /* ===== PAGE HEADER CARD ===== */
    .ph-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        padding: 16px 20px; display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
        margin-bottom: 1.25rem; position: relative; overflow: hidden;
        box-shadow: 0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; border-radius: 14px 0 0 14px;
    }
    .ph-card.index-page::before { background: #7c3aed; }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon {
        width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
    }
    .ph-icon.index  { background: #f5f3ff; color: #7c3aed; }
    .ph-title { font-size: 1.05rem; font-weight: 700; color: #1e293b; letter-spacing: -.2px; line-height: 1.2; margin: 0; }
    .ph-breadcrumb {
        display: flex; align-items: center; gap: 4px; flex-wrap: wrap;
        margin-top: 4px; list-style: none; padding: 0; margin-bottom: 0;
    }
    .ph-breadcrumb li { display: flex; align-items: center; }
    .ph-breadcrumb li+li::before { content: '›'; color: #cbd5e1; font-size: .7rem; margin: 0 4px; }
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ===== STAT CARDS ===== */
    .stat-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 12px;
        padding: 16px; box-shadow: 0 1px 5px rgba(0,0,0,.04);
    }
    .stat-icon {
        width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: .9rem;
    }
    .stat-icon.purple { background: #f5f3ff; color: #7c3aed; }
    .stat-icon.green  { background: #dcfce7; color: #16a34a; }
    .stat-icon.slate  { background: #f1f5f9; color: #64748b; }
    .stat-icon.blue   { background: #e8f0fe; color: #1a73e8; }
    .stat-value { font-size: 1.45rem; font-weight: 800; color: #1e293b; line-height: 1; }
    .stat-label { font-size: .72rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; margin-top: 2px; }

    /* ===== FILTER BAR ===== */
    .filter-bar {
        background: #fff; border: 1px solid #e9ecef; border-radius: 12px;
        padding: 14px 18px; margin-bottom: 1.25rem;
        box-shadow: 0 1px 5px rgba(0,0,0,.04);
    }
    .filter-bar .form-control,
    .filter-bar .form-select {
        border-radius: 8px; border: 1.5px solid #e2e8f0;
        font-size: .82rem; background: #f8fafc;
        padding: 7px 11px; color: #334155;
    }
    .filter-bar .form-control:focus,
    .filter-bar .form-select:focus { border-color: #7c3aed; background: #fff; box-shadow: 0 0 0 3px rgba(124,58,237,.1); }

    /* ===== PENGURUS GRID ===== */
    .pengurus-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.04);
        transition: transform .15s, box-shadow .15s;
    }
    .pengurus-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
    .pengurus-card-top {
        background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
        padding: 20px 16px 36px; position: relative; text-align: center;
    }
    .pengurus-card-top::before {
        content: ''; position: absolute; top: -30px; right: -30px;
        width: 100px; height: 100px; border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .pengurus-avatar {
        width: 72px; height: 72px; border-radius: 50%;
        object-fit: cover; border: 3px solid rgba(255,255,255,.6);
        box-shadow: 0 3px 12px rgba(0,0,0,.2); background: #e9ecef;
    }
    .pengurus-avatar-placeholder {
        width: 72px; height: 72px; border-radius: 50%;
        border: 3px solid rgba(255,255,255,.5);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; font-weight: 800; background: rgba(255,255,255,.18);
        color: #fff; box-shadow: 0 3px 12px rgba(0,0,0,.15); margin: 0 auto;
    }
    .pengurus-card-body {
        padding: 36px 16px 16px; margin-top: -28px; position: relative;
        background: #fff;
    }
    .pengurus-name { font-size: .95rem; font-weight: 700; color: #1e293b; text-align: center; margin-bottom: 2px; }
    .pengurus-jabatan { font-size: .76rem; color: #64748b; text-align: center; margin-bottom: 12px; }

    .badge-status {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px; font-size: .7rem; font-weight: 600;
    }
    .badge-aktif    { background: #dcfce7; color: #15803d; }
    .badge-nonaktif { background: #fee2e2; color: #b91c1c; }
    .badge-akun     { background: #e8f0fe; color: #1a73e8; }

    .pengurus-info-row { font-size: .78rem; color: #64748b; display: flex; align-items: center; gap: 6px; padding: 3px 0; }
    .pengurus-info-row i { width: 14px; text-align: center; opacity: .6; }

    .btn-action {
        padding: 5px 11px; font-size: .75rem; border-radius: 8px;
        border: none; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 4px; font-weight: 500;
    }
    .btn-action.view  { background: #f5f3ff; color: #7c3aed; }
    .btn-action.edit  { background: #fff4ed; color: #e96c1a; }
    .btn-action.del   { background: #fee2e2; color: #dc2626; }
    .btn-action:hover { opacity: .8; }

    /* ===== ALERT ===== */
    .alert { border: none; border-radius: 12px; font-size: .84rem; }
    .alert-success { background: #dcfce7; color: #166534; }
    .alert-danger  { background: #fee2e2; color: #991b1b; }

    /* empty state */
    .empty-state { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
    .empty-state i { font-size: 2.5rem; opacity: .3; margin-bottom: 12px; display: block; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Header --}}
    <div class="ph-card index-page">
        <div class="ph-left">
            <div class="ph-icon index"><i class="fas fa-id-badge"></i></div>
            <div>
                <h5 class="ph-title">Data Pengurus Panti</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><span class="bc-active">Pengurus</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('pengurus.create') }}" class="btn btn-sm" style="background:#7c3aed;color:#fff;">
            <i class="fas fa-user-plus me-1"></i> Tambah Pengurus
        </a>
    </div>

    <div class="page-inner">

        {{-- Flash --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Stat Row --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon purple"><i class="fas fa-id-badge"></i></div>
                        <div>
                            <div class="stat-value">{{ $pengurus->total() }}</div>
                            <div class="stat-label">Total</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
                        <div>
                            <div class="stat-value">{{ $totalAktif }}</div>
                            <div class="stat-label">Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon slate"><i class="fas fa-user-slash"></i></div>
                        <div>
                            <div class="stat-value">{{ $totalNonaktif }}</div>
                            <div class="stat-label">Non-aktif</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon blue"><i class="fas fa-user-shield"></i></div>
                        <div>
                            <div class="stat-value">{{ $totalPunyaAkun }}</div>
                            <div class="stat-label">Punya Akun</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('pengurus.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari nama, jabatan, email…"
                        value="{{ request('search') }}">
                </div>
                @if(auth()->user()->isAdminDinsos())
                <div class="col-md-3">
                    <select name="panti_id" class="form-select">
                        <option value="">Semua Panti</option>
                        @foreach($pantis as $panti)
                            <option value="{{ $panti->id }}" {{ request('panti_id') == $panti->id ? 'selected' : '' }}>
                                {{ $panti->nama_panti }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-sm" style="background:#7c3aed;color:#fff;">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                    @if(request()->hasAny(['search','panti_id','status']))
                    <a href="{{ route('pengurus.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Grid --}}
        @if($pengurus->isEmpty())
            <div class="empty-state">
                <i class="fas fa-id-badge"></i>
                <p class="mb-1 fw-600" style="font-weight:600">Belum ada data pengurus</p>
                <p class="small">Tambahkan pengurus panti pertama</p>
             
            </div>
        @else
        <div class="row g-3">
            @foreach($pengurus as $item)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="pengurus-card">
                    <div class="pengurus-card-top">
                        @if($item->foto && file_exists(storage_path('app/public/' . $item->foto)))
                            <img src="{{ asset('storage/' . $item->foto) }}"
                                 alt="{{ $item->nama }}" class="pengurus-avatar">
                        @else
                            <div class="pengurus-avatar-placeholder">
                                {{ strtoupper(substr($item->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="pengurus-card-body">
                        <div class="pengurus-name">{{ $item->nama }}</div>
                        <div class="pengurus-jabatan">{{ $item->jabatan ?? 'Pengurus' }}</div>

                        <div class="d-flex justify-content-center gap-1 mb-3 flex-wrap">
                            @if($item->status === 'aktif')
                                <span class="badge-status badge-aktif">
                                    <i class="fas fa-circle" style="font-size:.45rem;"></i> Aktif
                                </span>
                            @else
                                <span class="badge-status badge-nonaktif">
                                    <i class="fas fa-circle" style="font-size:.45rem;"></i> Non-aktif
                                </span>
                            @endif
                            @if($item->user_id)
                                <span class="badge-status badge-akun">
                                    <i class="fas fa-key" style="font-size:.65rem;"></i> Punya Akun
                                </span>
                            @endif
                        </div>

                        @if($item->pantiAsuhan)
                        <div class="pengurus-info-row">
                            <i class="fas fa-hospital"></i>
                            <span>{{ Str::limit($item->pantiAsuhan->nama_panti, 28) }}</span>
                        </div>
                        @endif
                        @if($item->no_telp)
                        <div class="pengurus-info-row">
                            <i class="fas fa-phone"></i>
                            <span>{{ $item->no_telp }}</span>
                        </div>
                        @endif
                        @if($item->email)
                        <div class="pengurus-info-row">
                            <i class="fas fa-envelope"></i>
                            <span>{{ Str::limit($item->email, 26) }}</span>
                        </div>
                        @endif

                        <div class="d-flex gap-1 mt-3">
                            <a href="{{ route('pengurus.show', $item) }}" class="btn-action view flex-grow-1 justify-content-center">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('pengurus.edit', $item) }}" class="btn-action edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('pengurus.destroy', $item) }}" method="POST"
                                  onsubmit="return confirm('Hapus pengurus {{ addslashes($item->nama) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action del">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($pengurus->hasPages())
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
            <small class="text-muted">
                Menampilkan {{ $pengurus->firstItem() }}–{{ $pengurus->lastItem() }} dari {{ $pengurus->total() }} pengurus
            </small>
            {{ $pengurus->links() }}
        </div>
        @endif
        @endif

    </div>{{-- end .page-inner --}}
</div>{{-- end .container --}}
@endsection
