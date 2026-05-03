@extends('layouts.user.user')

@section('title', 'Detail Donatur - ' . $donatur->nama)

@section('styles')
<style>
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

    .profile-header {
        background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
        border-radius: 12px 12px 0 0; padding: 2rem;
        position: relative; overflow: hidden; color: #fff;
    }
    .profile-header::before {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 180px; height: 180px; border-radius: 50%; background: rgba(255,255,255,.07);
    }
    .profile-avatar {
        width: 80px; height: 80px; border-radius: 50%; object-fit: cover;
        border: 3px solid rgba(255,255,255,.5); box-shadow: 0 4px 16px rgba(0,0,0,.2); flex-shrink: 0;
    }
    .profile-avatar-placeholder {
        width: 80px; height: 80px; border-radius: 50%; flex-shrink: 0;
        border: 3px solid rgba(255,255,255,.4);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 800; background: rgba(255,255,255,.18); color: #fff;
    }
    .profile-header .nama { font-size: 1.2rem; font-weight: 700; margin-bottom: 4px; }
    .profile-header .sub  { font-size: .82rem; opacity: .8; }

    .info-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; margin-bottom: 2px; }
    .info-value { font-size: .88rem; font-weight: 500; color: #1e293b; }
    .info-row   { border-bottom: 1px solid #f1f5f9; padding: 10px 0; }
    .info-row:last-child { border-bottom: none; }
    .section-label {
        font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
        color: #0891b2; padding: 8px 0 6px; border-bottom: 2px solid #e9ecef; margin-bottom: 10px;
    }

    .badge-jenis {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 12px; border-radius: 20px; font-size: .78rem; font-weight: 600;
    }
    .badge-perorangan { background: #e0f2fe; color: #0891b2; }
    .badge-organisasi { background: #f5f3ff; color: #7c3aed; }
    .badge-perusahaan { background: #fef3c7; color: #b45309; }
    .badge-pemerintah { background: #dcfce7; color: #15803d; }
    .badge-aktif      { background: #dcfce7; color: #15803d; border-radius: 20px; padding: 3px 12px; font-size: .78rem; font-weight: 600; }
    .badge-nonaktif   { background: #fee2e2; color: #b91c1c; border-radius: 20px; padding: 3px 12px; font-size: .78rem; font-weight: 600; }

    /* ── Donasi stat boxes ── */
    .donasi-stat {
        background: #f8fafc; border: 1px solid #e9ecef; border-radius: 12px;
        padding: 14px 16px; text-align: center;
    }
    .donasi-stat .ds-val  { font-size: 1.3rem; font-weight: 800; color: #1e293b; line-height: 1; }
    .donasi-stat .ds-lbl  { font-size: .68rem; color: #64748b; margin-top: 3px; font-weight: 600;
                             text-transform: uppercase; letter-spacing: .04em; }
    .donasi-stat .ds-icon { font-size: 1.1rem; margin-bottom: 6px; }

    /* ── Donasi table card ── */
    .donasi-table-card {
        background: #fff; border: 1px solid #e9ecef; border-radius: 14px;
        overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.04);
    }
    .donasi-table-card .dtc-header {
        padding: 13px 18px; border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;
    }
    .donasi-table-card .dtc-title { font-size: .9rem; font-weight: 700; color: #1e293b; }

    .table > thead > tr > th {
        background: #f8fafc; font-size: .73rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em; color: #64748b;
        padding: 10px 14px; border-bottom: 1px solid #e9ecef; white-space: nowrap;
    }
    .table > tbody > tr > td {
        padding: 11px 14px; font-size: .82rem; color: #374151;
        vertical-align: middle; border-bottom: 1px solid #f8fafc;
    }
    .table > tbody > tr:last-child > td { border-bottom: none; }
    .table > tbody > tr:hover > td { background: #fafbff; }

    /* ── Status badges donasi ── */
    .badge-donasi {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px; font-size: .7rem; font-weight: 600;
    }
    .badge-pending  { background: #fef9c3; color: #854d0e; }
    .badge-diterima { background: #dcfce7; color: #15803d; }
    .badge-ditolak  { background: #fee2e2; color: #b91c1c; }

    /* ── Jenis/metode chips ── */
    .chip {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 2px 8px; border-radius: 6px; font-size: .68rem; font-weight: 600;
    }
    .chip-uang     { background: #dcfce7; color: #15803d; }
    .chip-barang   { background: #fef3c7; color: #b45309; }
    .chip-online   { background: #e0f2fe; color: #0891b2; }
    .chip-kunjungan{ background: #f5f3ff; color: #7c3aed; }

    /* ── Empty donasi ── */
    .empty-donasi { text-align: center; padding: 2.5rem 1rem; color: #94a3b8; }
    .empty-donasi i { font-size: 2rem; opacity: .3; display: block; margin-bottom: 8px; }
</style>
@endsection

@section('content')
<div class="container">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-eye"></i></div>
            <div>
                <h5 class="ph-title">Detail Donatur</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('donatur.index') }}">Donatur</a></li>
                    <li><span class="bc-active">{{ $donatur->nama }}</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('donatur.edit', $donatur) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <button class="btn btn-danger btn-sm" id="btn-hapus">
                <i class="fas fa-trash me-1"></i> Hapus
            </button>
            <form id="form-hapus" action="{{ route('donatur.destroy', $donatur) }}" method="POST" class="d-none">
                @csrf @method('DELETE')
            </form>
            <a href="{{ route('donatur.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-inner">
        <div class="row g-4">

            <div class="col-lg-8">
                <div class="card shadow-sm mb-4 overflow-hidden">
                    <div class="profile-header">
                        <div class="d-flex align-items-center gap-3">
                            @if($donatur->foto && file_exists(storage_path('app/public/'.$donatur->foto)))
                                <img src="{{ asset('storage/'.$donatur->foto) }}" alt="{{ $donatur->nama }}" class="profile-avatar">
                            @else
                                <div class="profile-avatar-placeholder">{{ strtoupper(substr($donatur->nama,0,1)) }}</div>
                            @endif
                            <div style="position:relative;z-index:1">
                                <div class="nama">{{ $donatur->nama }}</div>
                                <div class="sub">
                                    <span class="badge-jenis badge-{{ $donatur->jenis_donatur }}" style="font-size:.75rem;">
                                        {{ ucfirst($donatur->jenis_donatur) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-id-card me-1"></i>Informasi Donatur</div>

                        <div class="info-row d-flex justify-content-between align-items-center">
                            <div class="info-label">Status</div>
                            <span class="badge-{{ $donatur->status }}">{{ $donatur->status === 'aktif' ? 'Aktif' : 'Non-aktif' }}</span>
                        </div>
                        <div class="info-row">
                            <div class="info-label">No. Telepon</div>
                            <div class="info-value">
                                @if($donatur->no_telp)
                                    <a href="tel:{{ $donatur->no_telp }}">{{ $donatur->no_telp }}</a>
                                @else - @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value">
                                @if($donatur->email)
                                    <a href="mailto:{{ $donatur->email }}">{{ $donatur->email }}</a>
                                @else - @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Alamat</div>
                            <div class="info-value" style="white-space:pre-line">{{ $donatur->alamat ?? '-' }}</div>
                        </div>
                        @if($donatur->keterangan)
                        <div class="info-row">
                            <div class="info-label">Keterangan</div>
                            <div class="info-value" style="white-space:pre-line">{{ $donatur->keterangan }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ── Riwayat Donasi ── --}}
                <div class="donasi-table-card mb-4">
                    <div class="dtc-header">
                        <span class="dtc-title">
                            <i class="fas fa-hand-holding-heart me-2" style="color:#0891b2;"></i>Riwayat Donasi
                        </span>
                        <span class="chip chip-uang" style="font-size:.72rem;">{{ $totalDonasi }} donasi total</span>
                    </div>

                    {{-- Stat donasi --}}
                    <div class="row g-0 border-bottom" style="border-color:#f1f5f9!important;">
                        <div class="col-3 border-end" style="border-color:#f1f5f9!important;">
                            <div class="donasi-stat">
                                <div class="ds-icon" style="color:#0891b2;">📋</div>
                                <div class="ds-val">{{ $totalDonasi }}</div>
                                <div class="ds-lbl">Total</div>
                            </div>
                        </div>
                        <div class="col-3 border-end" style="border-color:#f1f5f9!important;">
                            <div class="donasi-stat">
                                <div class="ds-icon" style="color:#16a34a;">✅</div>
                                <div class="ds-val">{{ $totalDiterima }}</div>
                                <div class="ds-lbl">Diterima</div>
                            </div>
                        </div>
                        <div class="col-3 border-end" style="border-color:#f1f5f9!important;">
                            <div class="donasi-stat">
                                <div class="ds-icon" style="color:#16a34a;">💰</div>
                                <div class="ds-val" style="font-size:1rem;">
                                    @if($totalNominal >= 1000000)
                                        {{ number_format($totalNominal/1000000, 1) }}jt
                                    @elseif($totalNominal >= 1000)
                                        {{ number_format($totalNominal/1000, 0) }}rb
                                    @else
                                        {{ number_format($totalNominal, 0) }}
                                    @endif
                                </div>
                                <div class="ds-lbl">Nominal (IDR)</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="donasi-stat">
                                <div class="ds-icon" style="color:#b45309;">📦</div>
                                <div class="ds-val">{{ $totalBarang }}</div>
                                <div class="ds-lbl">Donasi Barang</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel donasi --}}
                    @if($donasis->isEmpty())
                        <div class="empty-donasi">
                            <i class="fas fa-hand-holding-heart"></i>
                            <p class="mb-0 small">Donatur ini belum pernah melakukan donasi</p>
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Panti</th>
                                    <th>Jenis</th>
                                    <th>Detail</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donasis as $d)
                                <tr>
                                    <td style="white-space:nowrap;">
                                        <div style="font-weight:600;">{{ $d->tanggal_donasi->format('d M Y') }}</div>
                                        <span class="chip chip-{{ $d->metode }}" style="margin-top:3px;">
                                            <i class="fas fa-{{ $d->metode === 'online' ? 'wifi' : 'walking' }}" style="font-size:.6rem;"></i>
                                            {{ ucfirst($d->metode) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="font-size:.8rem;font-weight:500;max-width:130px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $d->pantiAsuhan->nama_panti ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="chip chip-{{ $d->jenis_donasi }}">
                                            <i class="fas fa-{{ $d->jenis_donasi === 'uang' ? 'money-bill' : 'box' }}" style="font-size:.6rem;"></i>
                                            {{ ucfirst($d->jenis_donasi) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($d->jenis_donasi === 'uang')
                                            <div style="font-weight:600;color:#15803d;">
                                                Rp {{ number_format($d->nominal, 0, ',', '.') }}
                                            </div>
                                        @else
                                            <div style="font-size:.8rem;">
                                                @if($d->nama_barang)
                                                    <span style="font-weight:600;">{{ $d->nama_barang }}</span>
                                                    @if($d->jumlah_barang)
                                                        — {{ $d->jumlah_barang }} {{ $d->satuan_barang }}
                                                    @endif
                                                @else
                                                    <span class="text-muted">{{ Str::limit($d->deskripsi_barang, 35) ?? '-' }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-donasi badge-{{ $d->status }}">
                                            @if($d->status === 'pending')
                                                <i class="fas fa-clock" style="font-size:.55rem;"></i> Pending
                                            @elseif($d->status === 'diterima')
                                                <i class="fas fa-check" style="font-size:.55rem;"></i> Diterima
                                            @else
                                                <i class="fas fa-times" style="font-size:.55rem;"></i> Ditolak
                                            @endif
                                        </span>
                                        @if($d->status === 'diterima' && $d->dikonfirmasiOleh)
                                        <div class="text-muted mt-1" style="font-size:.68rem;">
                                            oleh {{ $d->dikonfirmasiOleh->username ?? '-' }}
                                        </div>
                                        @endif
                                        @if($d->status === 'ditolak' && $d->alasan_tolak)
                                        <div class="mt-1" style="font-size:.68rem;color:#b91c1c;max-width:120px;">
                                            {{ Str::limit($d->alasan_tolak, 30) }}
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($donasis->hasPages())
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 px-3 py-2"
                         style="background:#f8fafc;border-top:1px solid #f1f5f9;">
                        <small class="text-muted">
                            {{ $donasis->firstItem() }}–{{ $donasis->lastItem() }} dari {{ $donasis->total() }}
                        </small>
                        {{ $donasis->links() }}
                    </div>
                    @endif
                    @endif

                </div>{{-- end donasi-table-card --}}

            </div>

            <div class="col-lg-4">

                {{-- Akun --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-user-shield me-1"></i>Akun Login</div>
                        @if($donatur->user)
                        <div class="info-row">
                            <div class="info-label">Username</div>
                            <div class="info-value">{{ $donatur->user->username }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email Login</div>
                            <div class="info-value">{{ $donatur->user->email }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Status Akun</div>
                            <div class="info-value">
                                <span class="badge-{{ $donatur->user->status }}">{{ ucfirst($donatur->user->status) }}</span>
                            </div>
                        </div>
                        @else
                        <p class="text-muted small mb-0">Donatur ini belum memiliki akun login.</p>
                        @endif
                    </div>
                </div>

                {{-- Meta --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-clock me-1"></i>Riwayat</div>
                        <div class="info-row">
                            <div class="info-label">Ditambahkan</div>
                            <div class="info-value">{{ $donatur->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Diperbarui</div>
                            <div class="info-value">{{ $donatur->updated_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('donatur.edit', $donatur) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit Data
                    </a>
                    <a href="{{ route('donatur.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('btn-hapus').addEventListener('click', function () {
        if (confirm('Yakin ingin menghapus donatur "{{ addslashes($donatur->nama) }}"?')) {
            document.getElementById('form-hapus').submit();
        }
    });
</script>
@endsection
