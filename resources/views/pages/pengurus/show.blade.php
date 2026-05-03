@extends('layouts.user.user')

@section('title', 'Detail Pengurus - ' . $pengurus->nama)

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
    .ph-breadcrumb a { font-size: .75rem; color: #1a73e8; text-decoration: none; }
    .ph-breadcrumb a:hover { text-decoration: underline; }
    .ph-breadcrumb .bc-active { font-size: .75rem; color: #94a3b8; }

    /* ===== PROFILE HEADER ===== */
    .profile-header {
        background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
        border-radius: 12px 12px 0 0; padding: 2rem;
        position: relative; overflow: hidden; color: #fff;
    }
    .profile-header::before {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .profile-avatar {
        width: 80px; height: 80px; border-radius: 50%; object-fit: cover;
        border: 3px solid rgba(255,255,255,.5); box-shadow: 0 4px 16px rgba(0,0,0,.2);
        flex-shrink: 0; background: #e9ecef;
    }
    .profile-avatar-placeholder {
        width: 80px; height: 80px; border-radius: 50%; flex-shrink: 0;
        border: 3px solid rgba(255,255,255,.4);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 800;
        background: rgba(255,255,255,.18); color: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,.15);
    }
    .profile-header .nama  { font-size: 1.2rem; font-weight: 700; margin-bottom: 4px; }
    .profile-header .sub   { font-size: .82rem; opacity: .8; }

    /* ===== INFO ===== */
    .info-label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; margin-bottom: 2px; }
    .info-value { font-size: .88rem; font-weight: 500; color: #1e293b; }
    .info-row   { border-bottom: 1px solid #f1f5f9; padding: 10px 0; }
    .info-row:last-child { border-bottom: none; }
    .section-label {
        font-size: .75rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .06em; color: #7c3aed; padding: 8px 0 6px;
        border-bottom: 2px solid #e9ecef; margin-bottom: 10px;
    }

    /* ===== BADGE ===== */
    .badge-aktif    { background: #dcfce7; color: #15803d; border-radius: 20px; padding: 3px 12px; font-size: .78rem; font-weight: 600; }
    .badge-nonaktif { background: #fee2e2; color: #b91c1c; border-radius: 20px; padding: 3px 12px; font-size: .78rem; font-weight: 600; }
    .badge-akun     { background: #e8f0fe; color: #1a73e8; border-radius: 20px; padding: 3px 12px; font-size: .78rem; font-weight: 600; }

    /* ===== STAT ===== */
    .stat-box {
        background: #f8fafc; border: 1px solid #e9ecef; border-radius: 12px;
        padding: 14px; text-align: center;
    }
    .stat-box .num  { font-size: 1.5rem; font-weight: 800; color: #1e293b; line-height: 1; }
    .stat-box .lbl  { font-size: .7rem; color: #64748b; margin-top: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Header --}}
    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-eye"></i></div>
            <div>
                <h5 class="ph-title">Detail Pengurus</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('pengurus.index') }}">Pengurus</a></li>
                    <li><span class="bc-active">{{ $pengurus->nama }}</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('pengurus.edit', $pengurus) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <button class="btn btn-danger btn-sm" id="btn-hapus">
                <i class="fas fa-trash me-1"></i> Hapus
            </button>
            <form id="form-hapus" action="{{ route('pengurus.destroy', $pengurus) }}" method="POST" class="d-none">
                @csrf @method('DELETE')
            </form>
            <a href="{{ route('pengurus.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-inner">
        <div class="row g-4">

            {{-- Kolom Kiri --}}
            <div class="col-lg-8">

                {{-- Profile Card --}}
                <div class="card shadow-sm mb-4 overflow-hidden">
                    <div class="profile-header">
                        <div class="d-flex align-items-center gap-3">
                            @if($pengurus->foto && file_exists(storage_path('app/public/' . $pengurus->foto)))
                                <img src="{{ asset('storage/' . $pengurus->foto) }}"
                                     alt="{{ $pengurus->nama }}" class="profile-avatar">
                            @else
                                <div class="profile-avatar-placeholder">
                                    {{ strtoupper(substr($pengurus->nama, 0, 1)) }}
                                </div>
                            @endif
                            <div style="position:relative;z-index:1">
                                <div class="nama">{{ $pengurus->nama }}</div>
                                <div class="sub">
                                    {{ $pengurus->jabatan ?? 'Pengurus' }} —
                                    {{ $pengurus->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </div>
                                @if($pengurus->pantiAsuhan)
                                    <div class="sub mt-1">
                                        <i class="fas fa-hospital me-1"></i>{{ $pengurus->pantiAsuhan->nama_panti }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-id-card me-1"></i>Data Diri</div>

                        <div class="info-row d-flex justify-content-between">
                            <div class="info-label">Status</div>
                            <div>
                                @if($pengurus->status === 'aktif')
                                    <span class="badge-aktif">Aktif</span>
                                @else
                                    <span class="badge-nonaktif">Non-aktif</span>
                                @endif
                                @if($pengurus->user_id)
                                    <span class="badge-akun ms-1">
                                        <i class="fas fa-key me-1"></i>Punya Akun
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="info-row pe-3">
                                    <div class="info-label">NIK</div>
                                    <div class="info-value">{{ $pengurus->nik ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row ps-md-3">
                                    <div class="info-label">Jenis Kelamin</div>
                                    <div class="info-value">{{ $pengurus->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row pe-3">
                                    <div class="info-label">Tempat Lahir</div>
                                    <div class="info-value">{{ $pengurus->tempat_lahir ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row ps-md-3">
                                    <div class="info-label">Tanggal Lahir</div>
                                    <div class="info-value">
                                        @if($pengurus->tanggal_lahir)
                                            {{ $pengurus->tanggal_lahir->translatedFormat('d F Y') }}
                                            <span class="text-muted">({{ $pengurus->usia }} tahun)</span>
                                        @else -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row pe-3">
                                    <div class="info-label">Pendidikan Terakhir</div>
                                    <div class="info-value">{{ $pengurus->pendidikan_terakhir ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row ps-md-3">
                                    <div class="info-label">Jabatan</div>
                                    <div class="info-value">{{ $pengurus->jabatan ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Alamat</div>
                            <div class="info-value">{{ $pengurus->alamat ?? '-' }}</div>
                        </div>

                    </div>
                </div>

                {{-- Kontak --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-address-book me-1"></i>Kontak</div>
                        <div class="info-row">
                            <div class="info-label">No. Telepon</div>
                            <div class="info-value">
                                @if($pengurus->no_telp)
                                    <a href="tel:{{ $pengurus->no_telp }}">{{ $pengurus->no_telp }}</a>
                                @else -
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value">
                                @if($pengurus->email)
                                    <a href="mailto:{{ $pengurus->email }}">{{ $pengurus->email }}</a>
                                @else -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan --}}
            <div class="col-lg-4">

                {{-- Panti --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-hospital me-1"></i>Panti Asuhan</div>
                        @if($pengurus->pantiAsuhan)
                        <div class="info-row">
                            <div class="info-label">Nama Panti</div>
                            <div class="info-value fw-semibold">{{ $pengurus->pantiAsuhan->nama_panti }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Alamat Panti</div>
                            <div class="info-value">{{ $pengurus->pantiAsuhan->alamat }}</div>
                        </div>
                        <a href="{{ route('panti-asuhan.show', $pengurus->pantiAsuhan) }}"
                           class="btn btn-sm w-100 mt-2" style="background:#f5f3ff;color:#7c3aed;border-radius:9px;">
                            <i class="fas fa-external-link-alt me-1"></i> Lihat Detail Panti
                        </a>
                        @else
                            <p class="text-muted small mb-0">-</p>
                        @endif
                    </div>
                </div>

                {{-- Akun --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-user-shield me-1"></i>Akun Login</div>
                        @if($pengurus->user)
                        <div class="info-row">
                            <div class="info-label">Nama Akun</div>
                            <div class="info-value">{{ $pengurus->user->name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email Login</div>
                            <div class="info-value">{{ $pengurus->user->email }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Status Akun</div>
                            <div class="info-value">
                                <span class="{{ $pengurus->user->status === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                                    {{ ucfirst($pengurus->user->status) }}
                                </span>
                            </div>
                        </div>
                        @else
                            <p class="text-muted small mb-2">Pengurus ini belum terhubung ke akun login.</p>
                        @endif
                    </div>
                </div>

                {{-- Meta --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-clock me-1"></i>Riwayat</div>
                        <div class="info-row">
                            <div class="info-label">Ditambahkan</div>
                            <div class="info-value">{{ $pengurus->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Diperbarui</div>
                            <div class="info-value">{{ $pengurus->updated_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="d-grid gap-2">
                    <a href="{{ route('pengurus.edit', $pengurus) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Edit Data
                    </a>
                    <a href="{{ route('pengurus.index') }}" class="btn btn-outline-secondary">
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
        if (confirm('Yakin ingin menghapus pengurus "{{ addslashes($pengurus->nama) }}"?')) {
            document.getElementById('form-hapus').submit();
        }
    });
</script>
@endsection
