@extends('layouts.user.user')
@section('title', 'Detail Donasi #' . $donasi->id)

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    *, .card, .btn { font-family: 'Plus Jakarta Sans', sans-serif; }

    .ph-card { background:#fff; border:1px solid #e9ecef; border-radius:14px; padding:18px 24px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:1.5rem; position:relative; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; background:#1269db; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; background:#e8f3ff; color:#1269db; }
    .ph-title { font-size:1.1rem; font-weight:700; color:#1e293b; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; list-style:none; padding:0; margin:4px 0 0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    .donasi-hero { border-radius:18px; padding:2rem 2.5rem; position:relative; overflow:hidden; margin-bottom:1.75rem; }
    .donasi-hero.uang   { background:linear-gradient(135deg,#1269db,#7c3aed); }
    .donasi-hero.barang { background:linear-gradient(135deg,#16a34a,#0d9488); }
    .donasi-hero::before { content:''; position:absolute; top:-40px; right:-40px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,.07); }
    .donasi-hero::after  { content:''; position:absolute; bottom:-60px; left:-30px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.05); }
    .hero-z { position:relative; z-index:1; }

    .section-divider { background:#f8f9fa; border-left:4px solid #1269db; padding:9px 14px; border-radius:0 6px 6px 0; font-weight:700; font-size:.85rem; color:#1269db; margin-bottom:1.2rem; display:flex; align-items:center; gap:8px; }
    .info-item { display:flex; align-items:flex-start; padding:14px 0; border-bottom:1px solid #f1f5f9; gap:12px; }
    .info-item:last-child { border-bottom:none; }
    .info-icon { width:36px; height:36px; border-radius:9px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:.82rem; }
    .info-label { font-size:.72rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#94a3b8; }
    .info-value { font-size:.9rem; font-weight:500; color:#1e293b; margin-top:2px; }

    .badge { font-size:.72rem; font-weight:600; padding:4px 10px; border-radius:7px; }
    .badge-pending  { background:#fffbeb; color:#92400e; }
    .badge-diterima { background:#dcfce7; color:#15803d; }
    .badge-ditolak  { background:#fee2e2; color:#dc2626; }

    .konfirmasi-panel { border:2px solid #bbf7d0; background:#f0fdf4; border-radius:14px; padding:22px; }
    .tolak-panel      { border:2px solid #fecaca; background:#fff5f5; border-radius:14px; padding:22px; margin-top:14px; }
    .keuangan-badge   { background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; border-radius:12px; padding:16px 20px; display:flex; align-items:center; gap:12px; }

    .form-control { border-radius:10px; border:1.5px solid #e2e8f0; font-size:.87rem; padding:9px 13px; background:#f8fafc; }
    .form-control:focus { border-color:#dc2626; box-shadow:0 0 0 3px rgba(220,38,38,.12); }

    .btn-primary { background:linear-gradient(135deg,#1a73e8,#1558b0); border:none; border-radius:10px; font-weight:600; font-size:.87rem; padding:9px 22px; }
    .btn-success { border-radius:10px; font-weight:600; font-size:.87rem; padding:9px 22px; }
    .btn-danger  { border-radius:10px; font-weight:600; font-size:.87rem; padding:9px 22px; }
    .btn-outline-secondary { border-radius:10px; font-size:.87rem; border-color:#e2e8f0; color:#64748b; }
    .alert { border-radius:12px; border:none; font-size:.85rem; padding:12px 18px; }
    .alert-success { background:#dcfce7; color:#15803d; }
    .alert-danger  { background:#fee2e2; color:#991b1b; }

    /* Tabel barang */
    .barang-table thead th { background:#f8fafc; color:#64748b; font-size:.7rem; font-weight:700; text-transform:uppercase; padding:10px 14px; border-bottom:2px solid #e2e8f0; border-top:none; }
    .barang-table tbody td { padding:11px 14px; font-size:.85rem; vertical-align:middle; border-bottom:1px solid #f1f5f9; }
    .barang-table tbody tr:last-child td { border-bottom:none; }
</style>
@endsection

@section('content')
<div class="container">

    <div class="ph-card">
        <div class="ph-left">
            <div class="ph-icon"><i class="fas fa-hand-holding-heart"></i></div>
            <div>
                <h5 class="ph-title">Detail Donasi #{{ $donasi->id }}</h5>
                <ol class="ph-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('donasi.index') }}">Donasi</a></li>
                    <li><span class="bc-active">Detail</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if(!$donasi->sudahDikonfirmasi())
                <a href="{{ route('donasi.edit',$donasi) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-pencil-alt me-1"></i> Edit
                </a>
            @endif
            <a href="{{ route('donasi.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex gap-2 align-items-center mb-4"><i class="fas fa-check-circle fs-5"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex gap-2 align-items-center mb-4"><i class="fas fa-exclamation-circle fs-5"></i>{{ session('error') }}</div>
    @endif

    {{-- Hero --}}
    <div class="donasi-hero {{ $donasi->jenis_donasi }}">
        <div class="hero-z d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="text-white">
                <div style="font-size:.82rem;opacity:.8;margin-bottom:4px;">
                    <i class="fas fa-{{ $donasi->jenis_donasi==='uang'?'money-bill-wave':'boxes' }} me-1"></i>
                    Donasi {{ ucfirst($donasi->jenis_donasi) }} &bull;
                    <i class="fas fa-{{ $donasi->metode==='online'?'wifi':'walking' }} ms-1 me-1"></i>
                    {{ ucfirst($donasi->metode) }}
                </div>
                <h3 class="fw-bold mb-1">
                    @if($donasi->jenis_donasi === 'uang')
                        Rp {{ number_format($donasi->nominal,0,',','.') }}
                    @else
                        {{ $donasi->barang->count() }} Jenis Barang
                        <span style="font-size:1rem;opacity:.8;">({{ $donasi->totalItemBarang() }} item)</span>
                    @endif
                </h3>
                <div class="d-flex align-items-center gap-3 flex-wrap" style="font-size:.84rem;opacity:.88;">
                    <span><i class="fas fa-user me-1"></i>{{ $donasi->donatur->nama ?? '-' }}</span>
                    <span><i class="fas fa-building me-1"></i>{{ $donasi->pantiAsuhan->nama_panti ?? '-' }}</span>
                    <span><i class="fas fa-calendar me-1"></i>{{ $donasi->tanggal_donasi?->format('d M Y') ?? '-' }}</span>
                </div>
            </div>
            <span class="badge badge-{{ $donasi->status }}" style="font-size:.85rem;padding:8px 16px;">
                @if($donasi->status==='pending')     <i class="fas fa-clock me-1"></i>
                @elseif($donasi->status==='diterima') <i class="fas fa-check-circle me-1"></i>
                @else                                 <i class="fas fa-times-circle me-1"></i>
                @endif
                {{ ucfirst($donasi->status) }}
            </span>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── Kolom Kiri ── --}}
        <div class="col-lg-7">

            {{-- Info Umum --}}
            <div class="card shadow-sm border-0 border-radius-16 mb-4" style="border-radius:16px;">
                <div class="card-body p-4">
                    <div class="section-divider"><i class="fas fa-info-circle"></i> Informasi Donasi</div>

                    <div class="info-item">
                        <div class="info-icon" style="background:#eff6ff;"><i class="fas fa-user text-primary"></i></div>
                        <div><div class="info-label">Donatur</div>
                            <div class="info-value fw-bold">{{ $donasi->donatur->nama ?? '-' }}</div>
                            <div style="font-size:.78rem;color:#94a3b8;">{{ ucfirst($donasi->donatur->jenis_donatur ?? '') }}</div>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon" style="background:#f0fdf4;"><i class="fas fa-building text-success"></i></div>
                        <div><div class="info-label">Panti Asuhan Tujuan</div>
                            <div class="info-value">{{ $donasi->pantiAsuhan->nama_panti ?? '-' }}</div></div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon" style="background:#f5f3ff;"><i class="fas fa-truck" style="color:#7c3aed;"></i></div>
                        <div><div class="info-label">Metode</div>
                            <div class="info-value">{{ $donasi->metode === 'online' ? '🌐 Online (Transfer/QRIS)' : '🚶 Kunjungan Langsung' }}</div></div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon" style="background:#fff7ed;"><i class="fas fa-calendar" style="color:#ea580c;"></i></div>
                        <div><div class="info-label">Tanggal Donasi</div>
                            <div class="info-value">{{ $donasi->tanggal_donasi?->translatedFormat('d F Y') ?? '-' }}</div></div>
                    </div>
                    @if($donasi->tanggal_kunjungan)
                    <div class="info-item">
                        <div class="info-icon" style="background:#fff7ed;"><i class="fas fa-calendar-check" style="color:#ea580c;"></i></div>
                        <div><div class="info-label">Tanggal Kunjungan</div>
                            <div class="info-value">{{ $donasi->tanggal_kunjungan->translatedFormat('d F Y') }}</div></div>
                    </div>
                    @endif
                    @if($donasi->catatan)
                    <div class="info-item">
                        <div class="info-icon" style="background:#f8f9fa;"><i class="fas fa-sticky-note text-secondary"></i></div>
                        <div><div class="info-label">Catatan</div>
                            <div class="info-value">{{ $donasi->catatan }}</div></div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Detail sesuai jenis --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius:16px;">
                <div class="card-body p-4">

                    @if($donasi->jenis_donasi === 'uang')
                        <div class="section-divider"><i class="fas fa-money-bill-wave"></i> Detail Uang</div>
                        <div class="info-item">
                            <div class="info-icon" style="background:#f0fdf4;"><i class="fas fa-coins text-success"></i></div>
                            <div><div class="info-label">Nominal</div>
                                <div class="info-value fw-bold text-success" style="font-size:1.15rem;">
                                    Rp {{ number_format($donasi->nominal,0,',','.') }}
                                </div></div>
                        </div>
                        @if($donasi->bukti_transfer)
                        <div class="info-item">
                            <div class="info-icon" style="background:#eff6ff;"><i class="fas fa-image text-primary"></i></div>
                            <div style="flex:1;">
                                <div class="info-label">Bukti Transfer</div>
                                <a href="{{ asset('storage/'.$donasi->bukti_transfer) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$donasi->bukti_transfer) }}"
                                         style="width:100%;max-width:280px;border-radius:10px;border:2px solid #e2e8f0;margin-top:8px;">
                                </a>
                            </div>
                        </div>
                        @endif

                    @else
                        <div class="section-divider"><i class="fas fa-boxes"></i> Daftar Barang ({{ $donasi->barang->count() }} jenis)</div>

                        @if($donasi->deskripsi_barang)
                            <p class="text-muted mb-3" style="font-size:.85rem;">{{ $donasi->deskripsi_barang }}</p>
                        @endif

                        <div class="table-responsive">
                            <table class="table barang-table mb-0">
                                <thead><tr>
                                    <th>#</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Foto</th>
                                </tr></thead>
                                <tbody>
                                @forelse($donasi->barang as $i => $b)
                                <tr>
                                    <td class="text-muted">{{ $i+1 }}</td>
                                    <td class="fw-semibold">{{ $b->nama_barang }}</td>
                                    <td>{{ $b->jumlah_barang }} {{ $b->satuan_barang }}</td>
                                    <td style="color:#64748b;">{{ $b->keterangan ?? '-' }}</td>
                                    <td>
                                        @if($b->foto_barang)
                                            <a href="{{ asset('storage/'.$b->foto_barang) }}" target="_blank">
                                                <img src="{{ asset('storage/'.$b->foto_barang) }}"
                                                     style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0;">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada item barang</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ── Kolom Kanan ── --}}
        <div class="col-lg-5">

            {{-- PANEL KONFIRMASI/TOLAK (hanya admin, donasi masih pending) --}}
            @if($donasi->status === 'pending' && in_array(auth()->user()->role, ['admin_dinsos','admin_panti']))

                <div class="konfirmasi-panel mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-check-circle text-success fs-5"></i>
                        <span class="fw-bold" style="color:#166534;">Terima Donasi</span>
                    </div>
                    <p class="text-muted mb-3" style="font-size:.8rem;line-height:1.75;">
                        Mengkonfirmasi donasi ini akan mengubah statusnya menjadi <strong>Diterima</strong>.
                        @if($donasi->jenis_donasi === 'uang')
                            Donasi uang otomatis dicatat sebagai <strong>pemasukan keuangan</strong>.
                        @endif
                    </p>
                    <form action="{{ route('donasi.konfirmasi',$donasi) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menerima donasi ini?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-2"></i> Terima Donasi
                        </button>
                    </form>
                </div>

                <div class="tolak-panel mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-times-circle text-danger fs-5"></i>
                        <span class="fw-bold" style="color:#991b1b;">Tolak Donasi</span>
                    </div>
                    <form action="{{ route('donasi.tolak',$donasi) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label" style="font-size:.83rem;font-weight:600;color:#991b1b;">
                                Alasan Penolakan <span style="color:#dc2626;">*</span>
                            </label>
                            <textarea name="alasan_tolak" rows="3" class="form-control @error('alasan_tolak') is-invalid @enderror"
                                placeholder="Tuliskan alasan penolakan...">{{ old('alasan_tolak') }}</textarea>
                            @error('alasan_tolak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-times me-2"></i> Tolak Donasi
                        </button>
                    </form>
                </div>

            @elseif($donasi->status === 'diterima')
                {{-- Sudah diterima --}}
                <div class="card shadow-sm border-0 mb-4" style="border-radius:16px;">
                    <div class="card-body p-4">
                        <div class="info-item">
                            <div class="info-icon" style="background:#f0fdf4;"><i class="fas fa-user-check text-success"></i></div>
                            <div><div class="info-label">Dikonfirmasi Oleh</div>
                                <div class="info-value">{{ $donasi->dikonfirmasiOleh->username ?? '-' }}
                                    <span style="font-size:.75rem;color:#94a3b8;"> ({{ $donasi->dikonfirmasiOleh->role ?? '' }})</span>
                                </div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon" style="background:#f0fdf4;"><i class="fas fa-calendar-check text-success"></i></div>
                            <div><div class="info-label">Waktu Konfirmasi</div>
                                <div class="info-value">{{ $donasi->dikonfirmasi_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Keuangan yang terbuat (hanya donasi uang) --}}
                @if($donasi->keuangan)
                <div class="keuangan-badge mb-4">
                    <div style="background:rgba(255,255,255,.2);width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-coins fs-5"></i>
                    </div>
                    <div>
                        <div style="font-size:.72rem;opacity:.8;text-transform:uppercase;letter-spacing:.05em;">Tercatat di Keuangan</div>
                        <div class="fw-bold" style="font-size:1rem;">Rp {{ number_format($donasi->keuangan->nominal,0,',','.') }}</div>
                        <div style="font-size:.78rem;opacity:.85;">{{ $donasi->keuangan->kategori }} &bull; {{ $donasi->keuangan->tanggal?->format('d/m/Y') }}</div>
                    </div>
                </div>
                @endif

            @elseif($donasi->status === 'ditolak')
                <div class="card shadow-sm border-0 mb-4" style="border-radius:16px;border-left:4px solid #dc2626!important;">
                    <div class="card-body p-4">
                        <span class="badge badge-ditolak mb-3"><i class="fas fa-times-circle me-1"></i>Ditolak</span>
                        <div class="info-item">
                            <div class="info-icon" style="background:#fee2e2;"><i class="fas fa-user text-danger"></i></div>
                            <div><div class="info-label">Ditolak Oleh</div>
                                <div class="info-value">{{ $donasi->dikonfirmasiOleh->username ?? '-' }}</div></div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon" style="background:#fee2e2;"><i class="fas fa-comment-alt text-danger"></i></div>
                            <div><div class="info-label">Alasan</div>
                                <div class="info-value">{{ $donasi->alasan_tolak ?? '-' }}</div></div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Meta --}}
            <div class="card shadow-sm border-0" style="border-radius:16px;">
                <div class="card-body p-4">
                    <div class="section-divider"><i class="fas fa-info"></i> Info Sistem</div>
                    <div class="info-item">
                        <div class="info-icon" style="background:#f8f9fa;"><i class="fas fa-hashtag text-secondary"></i></div>
                        <div><div class="info-label">ID Donasi</div><div class="info-value">#{{ $donasi->id }}</div></div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon" style="background:#f8f9fa;"><i class="fas fa-plus-circle text-secondary"></i></div>
                        <div><div class="info-label">Dibuat</div>
                            <div class="info-value">{{ $donasi->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</div></div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon" style="background:#f8f9fa;"><i class="fas fa-sync text-secondary"></i></div>
                        <div><div class="info-label">Diperbarui</div>
                            <div class="info-value">{{ $donasi->updated_at?->diffForHumans() ?? '-' }}</div></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
