@extends('layouts.user.user')

@section('title', 'Data Donatur')

@section('styles')
{{-- Select2 --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
<style>
    /* ── Select2 theme ── */
    .select2-container--default .select2-selection--single {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        height: 38px !important; background: #f8fafc !important;
        display: flex !important; align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important; font-size: .82rem; color: #334155;
        padding-left: 11px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border-radius: 7px; border: 1.5px solid #e2e8f0; font-size: .82rem; padding: 6px 10px;
    }
    .select2-results__option { font-size: .82rem; padding: 8px 12px; }
    .select2-results__option--highlighted { background: #7c3aed !important; }
    .select2-dropdown { border: 1.5px solid #e2e8f0; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.1); }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #7c3aed !important; box-shadow: 0 0 0 3px rgba(124,58,237,.1) !important;
    }

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
        width: 4px; border-radius: 14px 0 0 14px; background: #0891b2;
    }
    .ph-left { display: flex; align-items: center; gap: 12px; }
    .ph-icon {
        width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
        background: #e0f2fe; color: #0891b2;
    }
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

    /* ── Stat cards ── */
    .stat-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 12px;
        padding: 16px; box-shadow: 0 1px 5px rgba(0,0,0,.04);
    }
    .stat-icon {
        width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: .9rem;
    }
    .stat-icon.cyan   { background: #e0f2fe; color: #0891b2; }
    .stat-icon.green  { background: #dcfce7; color: #16a34a; }
    .stat-icon.slate  { background: #f1f5f9; color: #64748b; }
    .stat-icon.blue   { background: #e8f0fe; color: #1a73e8; }
    .stat-value { font-size: 1.45rem; font-weight: 800; color: #1e293b; line-height: 1; }
    .stat-label { font-size: .72rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; margin-top: 2px; }

    /* ── Filter bar ── */
    .filter-bar {
        background: #fff; border: 1px solid #e9ecef; border-radius: 12px;
        padding: 14px 18px; margin-bottom: 1.25rem;
        box-shadow: 0 1px 5px rgba(0,0,0,.04);
    }
    .filter-bar .form-control {
        border-radius: 9px; border: 1.5px solid #e2e8f0;
        font-size: .82rem; background: #f8fafc; padding: 7px 11px;
    }
    .filter-bar .form-control:focus { border-color: #0891b2; box-shadow: 0 0 0 3px rgba(8,145,178,.1); }

    /* ── Donatur card grid ── */
    .donatur-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.04);
        transition: transform .15s, box-shadow .15s;
    }
    .donatur-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
    .donatur-card-top {
        background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
        padding: 20px 16px 36px; position: relative; text-align: center;
    }
    .donatur-card-top::before {
        content: ''; position: absolute; top: -30px; right: -30px;
        width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,.07);
    }
    .donatur-avatar {
        width: 72px; height: 72px; border-radius: 50%; object-fit: cover;
        border: 3px solid rgba(255,255,255,.6); box-shadow: 0 3px 12px rgba(0,0,0,.2);
        background: #e9ecef;
    }
    .donatur-avatar-placeholder {
        width: 72px; height: 72px; border-radius: 50%;
        border: 3px solid rgba(255,255,255,.5);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; font-weight: 800;
        background: rgba(255,255,255,.18); color: #fff;
        box-shadow: 0 3px 12px rgba(0,0,0,.15); margin: 0 auto;
    }
    .donatur-card-body {
        padding: 36px 16px 16px; margin-top: -28px;
        position: relative; background: #fff;
    }
    .donatur-name    { font-size: .95rem; font-weight: 700; color: #1e293b; text-align: center; margin-bottom: 2px; }
    .donatur-jenis   { font-size: .76rem; color: #64748b; text-align: center; margin-bottom: 12px; }

    .badge-jenis {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 9px; border-radius: 20px; font-size: .68rem; font-weight: 600;
    }
    .badge-perorangan  { background: #e0f2fe; color: #0891b2; }
    .badge-organisasi  { background: #f5f3ff; color: #7c3aed; }
    .badge-perusahaan  { background: #fef3c7; color: #b45309; }
    .badge-pemerintah  { background: #dcfce7; color: #15803d; }
    .badge-aktif       { background: #dcfce7; color: #15803d; border-radius: 20px; padding: 3px 10px; font-size: .7rem; font-weight: 600; }
    .badge-nonaktif    { background: #fee2e2; color: #b91c1c; border-radius: 20px; padding: 3px 10px; font-size: .7rem; font-weight: 600; }
    .badge-akun        { background: #e8f0fe; color: #1a73e8; border-radius: 20px; padding: 3px 10px; font-size: .7rem; font-weight: 600; }

    .info-row { font-size: .78rem; color: #64748b; display: flex; align-items: center; gap: 6px; padding: 3px 0; }
    .info-row i { width: 14px; text-align: center; opacity: .6; }

    .btn-action {
        padding: 5px 11px; font-size: .75rem; border-radius: 8px; border: none;
        cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; font-weight: 500;
    }
    .btn-action.view { background: #e0f2fe; color: #0891b2; }
    .btn-action.edit { background: #fff4ed; color: #e96c1a; }
    .btn-action.del  { background: #fee2e2; color: #dc2626; }
    .btn-action:hover { opacity: .8; }

    .alert { border: none; border-radius: 12px; font-size: .84rem; }
    .alert-success { background: #dcfce7; color: #166534; }

    .empty-state { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
    .empty-state i { font-size: 2.5rem; opacity: .3; margin-bottom: 12px; display: block; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-hand-holding-heart"></i></div>
            <div>
                <h5 class="ph-title">Data Donatur</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><span class="bc-active">Donatur</span></li>
                </ol>
            </div>
        </div>
        <a href="{{ route('donatur.create') }}" class="btn btn-sm" style="background:#0891b2;color:#fff;">
            <i class="fas fa-plus me-1"></i> Tambah Donatur
        </a>
    </div>

    <div class="page-inner">

        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        {{-- Stat Row --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon cyan"><i class="fas fa-hand-holding-heart"></i></div>
                        <div>
                            <div class="stat-value">{{ $totalDonatur }}</div>
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
            <form method="GET" action="{{ route('donatur.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari nama, no. telepon…"
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="jenis" class="form-select-s2" id="filter-jenis">
                        <option value="">Semua Jenis</option>
                        <option value="perorangan"  {{ request('jenis') === 'perorangan'  ? 'selected' : '' }}>Perorangan</option>
                        <option value="organisasi"  {{ request('jenis') === 'organisasi'  ? 'selected' : '' }}>Organisasi</option>
                        <option value="perusahaan"  {{ request('jenis') === 'perusahaan'  ? 'selected' : '' }}>Perusahaan</option>
                        <option value="pemerintah"  {{ request('jenis') === 'pemerintah'  ? 'selected' : '' }}>Pemerintah</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select-s2" id="filter-status">
                        <option value="">Semua Status</option>
                        <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="akun" class="form-select-s2" id="filter-akun">
                        <option value="">Semua Akun</option>
                        <option value="ya"    {{ request('akun') === 'ya'    ? 'selected' : '' }}>Punya Akun</option>
                        <option value="tidak" {{ request('akun') === 'tidak' ? 'selected' : '' }}>Tanpa Akun</option>
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-sm" style="background:#0891b2;color:#fff;">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                    @if(request()->hasAny(['search','jenis','status','akun']))
                    <a href="{{ route('donatur.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Grid --}}
        @if($donaturs->isEmpty())
        <div class="empty-state">
            <i class="fas fa-hand-holding-heart"></i>
            <p class="mb-1 fw-semibold">Belum ada data donatur</p>

        </div>
        @else
        <div class="row g-3">
            @foreach($donaturs as $d)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="donatur-card">
                    <div class="donatur-card-top">
                        @if($d->foto && file_exists(storage_path('app/public/'.$d->foto)))
                            <img src="{{ asset('storage/'.$d->foto) }}" alt="{{ $d->nama }}" class="donatur-avatar">
                        @else
                            <div class="donatur-avatar-placeholder">{{ strtoupper(substr($d->nama,0,1)) }}</div>
                        @endif
                    </div>
                    <div class="donatur-card-body">
                        <div class="donatur-name">{{ $d->nama }}</div>
                        <div class="donatur-jenis">
                            <span class="badge-jenis badge-{{ $d->jenis_donatur }}">{{ ucfirst($d->jenis_donatur) }}</span>
                        </div>

                        <div class="d-flex justify-content-center gap-1 mb-3 flex-wrap">
                            <span class="badge-{{ $d->status }}">{{ $d->status === 'aktif' ? 'Aktif' : 'Non-aktif' }}</span>
                            @if($d->user_id)
                            <span class="badge-akun"><i class="fas fa-key" style="font-size:.6rem;"></i> Punya Akun</span>
                            @endif
                        </div>

                        @if($d->no_telp)
                        <div class="info-row"><i class="fas fa-phone"></i><span>{{ $d->no_telp }}</span></div>
                        @endif
                        @if($d->email)
                        <div class="info-row"><i class="fas fa-envelope"></i><span>{{ Str::limit($d->email,26) }}</span></div>
                        @endif

                        <div class="d-flex gap-1 mt-3">
                            <a href="{{ route('donatur.show', $d) }}" class="btn-action view flex-grow-1 justify-content-center">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('donatur.edit', $d) }}" class="btn-action edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('donatur.destroy', $d) }}" method="POST"
                                  onsubmit="return confirm('Hapus donatur {{ addslashes($d->nama) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action del"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($donaturs->hasPages())
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
            <small class="text-muted">
                Menampilkan {{ $donaturs->firstItem() }}–{{ $donaturs->lastItem() }} dari {{ $donaturs->total() }} donatur
            </small>
            {{ $donaturs->links() }}
        </div>
        @endif
        @endif

    </div>{{-- end .page-inner --}}
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<script>
    $('#filter-jenis, #filter-status, #filter-akun').select2({
        minimumResultsForSearch: Infinity,
        width: '100%',
        placeholder: function(){ return $(this).data('placeholder') || 'Pilih...'; },
    });
</script>
@endsection
