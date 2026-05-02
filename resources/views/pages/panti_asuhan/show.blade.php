@extends('layouts.user.user')

@section('title', 'Detail - ' . $pantiAsuhan->nama_panti)

@section('styles')
<style>
    /* ===== HEADER PROFILE ===== */
    .panti-header {
        background: linear-gradient(135deg, #1269db 0%, #0d4fa3 100%);
        border-radius:12px 12px 0 0; padding:2rem; position:relative; overflow:hidden;
    }
    .panti-header::before {
        content:''; position:absolute; top:-40px; right:-40px;
        width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,.07);
    }
    .panti-header::after {
        content:''; position:absolute; bottom:-60px; left:-30px;
        width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,.05);
    }

    .info-label { font-size:.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#6c757d; margin-bottom:2px; }
    .info-value  { font-size:.9rem; font-weight:500; color:#212529; }
    .info-row    { border-bottom:1px solid #f0f0f0; padding:10px 0; }
    .info-row:last-child { border-bottom:none; }
    .section-label {
        font-size:.8rem; font-weight:700; text-transform:uppercase;
        letter-spacing:.06em; color:#1269db;
        padding:8px 0 4px; border-bottom:2px solid #e9ecef; margin-bottom:8px;
    }

    /* ===== PAGE HEADER ===== */
    .ph-card {
        background:#fff; border:1px solid #e9ecef; border-radius:14px;
        padding:16px 20px; display:flex; align-items:center;
        justify-content:space-between; gap:16px; flex-wrap:wrap;
        margin-bottom:1.25rem; position:relative; overflow:hidden;
        box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
    .ph-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:14px 0 0 14px; }
    .ph-card.show-page::before { background:#1269db; }
    .ph-left { display:flex; align-items:center; gap:12px; }
    .ph-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
    .ph-icon.show { background:#e8f3ff; color:#1269db; }
    .ph-title { font-size:1.05rem; font-weight:700; color:#1e293b; letter-spacing:-.2px; line-height:1.2; margin:0; }
    .ph-breadcrumb { display:flex; align-items:center; gap:4px; flex-wrap:wrap; margin-top:4px; list-style:none; padding:0; margin-bottom:0; }
    .ph-breadcrumb li { display:flex; align-items:center; }
    .ph-breadcrumb li+li::before { content:'›'; color:#cbd5e1; font-size:.7rem; margin:0 4px; }
    .ph-breadcrumb a         { font-size:.75rem; color:#1a73e8; text-decoration:none; }
    .ph-breadcrumb a:hover   { text-decoration:underline; }
    .ph-breadcrumb .bc-active { font-size:.75rem; color:#94a3b8; }

    /* ===== GALERI FOTO ===== */
    .galeri-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:12px; }
    .galeri-item {
        border-radius:10px; overflow:hidden; position:relative;
        border:2px solid #e2e8f0; cursor:pointer; background:#f8fafc;
        transition: transform .15s, box-shadow .15s;
    }
    .galeri-item:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,0,0,.12); }
    .galeri-item img  { width:100%; height:110px; object-fit:cover; display:block; }
    .galeri-item .cover-badge {
        position:absolute; top:6px; left:6px;
        font-size:.6rem; font-weight:700; padding:2px 7px;
        border-radius:4px; background:rgba(22,163,74,.85); color:#fff;
    }
    .galeri-item .galeri-ket {
        padding:5px 8px; font-size:.72rem;
        color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .galeri-empty {
        border:2px dashed #e2e8f0; border-radius:12px;
        padding:32px; text-align:center; color:#94a3b8;
    }

    /* ===== LIGHTBOX ===== */
    #lightbox-overlay {
        display:none; position:fixed; inset:0; background:rgba(0,0,0,.85);
        z-index:9999; align-items:center; justify-content:center; flex-direction:column;
    }
    #lightbox-overlay.show { display:flex; }
    #lightbox-overlay img { max-width:90vw; max-height:80vh; border-radius:8px; object-fit:contain; }
    #lightbox-overlay .lb-ket { color:#e2e8f0; font-size:.85rem; margin-top:10px; }
    #lightbox-overlay .lb-close {
        position:fixed; top:16px; right:20px; font-size:1.6rem;
        color:#fff; cursor:pointer; background:none; border:none; line-height:1;
    }
    #lightbox-overlay .lb-nav {
        position:fixed; top:50%; transform:translateY(-50%);
        font-size:2rem; color:#fff; cursor:pointer; background:none; border:none; padding:0 12px;
        opacity:.7; transition:opacity .15s;
    }
    #lightbox-overlay .lb-nav:hover { opacity:1; }
    #lightbox-overlay .lb-prev { left:12px; }
    #lightbox-overlay .lb-next { right:12px; }
</style>
@endsection

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="ph-card show-page">
        <div class="ph-left">
            <div class="ph-icon show"><i class="fas fa-eye"></i></div>
            <div>
                <h5 class="ph-title">Detail Panti Asuhan</h5>
                <ol class="ph-breadcrumb" aria-label="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('panti-asuhan.index') }}">Panti Asuhan</a></li>
                    <li><span class="bc-active">{{ $pantiAsuhan->nama_panti }}</span></li>
                </ol>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('panti-asuhan.edit', $pantiAsuhan) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <button class="btn btn-danger btn-sm" id="btn-hapus-detail">
                <i class="fas fa-trash me-1"></i> Hapus
            </button>
            <form id="form-hapus-detail" action="{{ route('panti-asuhan.destroy', $pantiAsuhan) }}"
                  method="POST" class="d-none">
                @csrf @method('DELETE')
            </form>
            <a href="{{ route('panti-asuhan.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="page-inner">
        <div class="row g-4">

            {{-- Kolom Kiri: Profil Singkat --}}
            <div class="col-lg-4">
                <div class="card shadow-sm overflow-hidden mb-4">
                    {{-- Header --}}
                    <div class="panti-header text-white">
                        <div class="position-relative" style="z-index:1">
                            <h5 class="fw-bold mb-1">{{ $pantiAsuhan->nama_panti }}</h5>
                            <div class="opacity-75 small mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $pantiAsuhan->kecamatan ?? '-' }}
                            </div>
                            <span class="badge {{ $pantiAsuhan->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($pantiAsuhan->status) }}
                            </span>
                        </div>
                    </div>

                    {{-- Info Singkat --}}
                    <div class="card-body">
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Total Anak Asuh</span>
                            <span class="info-value fw-bold text-primary">{{ $pantiAsuhan->jumlah_anak_aktif }} aktif</span>
                        </div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">No. Telepon</span>
                            <span class="info-value">
                                @if($pantiAsuhan->no_telp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pantiAsuhan->no_telp) }}" target="_blank">
                                        {{ $pantiAsuhan->no_telp }}
                                    </a>
                                @else - @endif
                            </span>
                        </div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Nama Kontak</span>
                            <span class="info-value">{{ $pantiAsuhan->nama_kontak ?? '-' }}</span>
                        </div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Email</span>
                            <span class="info-value" style="font-size:.82rem;">{{ $pantiAsuhan->email ?? '-' }}</span>
                        </div>
                        <div class="info-row d-flex justify-content-between">
                            <span class="info-label">Foto Terdaftar</span>
                            <span class="info-value">{{ $pantiAsuhan->fotoPanti->count() }} foto</span>
                        </div>
                    </div>
                </div>

                @if($pantiAsuhan->keterangan)
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="section-label"><i class="fas fa-sticky-note me-2"></i>Keterangan</div>
                        <p class="mb-0 text-muted" style="font-size:.88rem;">{{ $pantiAsuhan->keterangan }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Kolom Kanan: Detail & Galeri --}}
            <div class="col-lg-8">

                {{-- Alamat & Kontak Lengkap --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-map-marked-alt text-primary me-2"></i>Informasi Lengkap</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="info-label">Alamat</div>
                                <div class="info-value">{{ $pantiAsuhan->alamat }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Kelurahan</div>
                                <div class="info-value">{{ $pantiAsuhan->kelurahan ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Kecamatan</div>
                                <div class="info-value">{{ $pantiAsuhan->kecamatan ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Nama Kontak / Ketua</div>
                                <div class="info-value">{{ $pantiAsuhan->nama_kontak ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">No. Telepon</div>
                                <div class="info-value">
                                    @if($pantiAsuhan->no_telp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pantiAsuhan->no_telp) }}" target="_blank">
                                            <i class="fab fa-whatsapp text-success me-1"></i>{{ $pantiAsuhan->no_telp }}
                                        </a>
                                    @else - @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $pantiAsuhan->email ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    <span class="badge {{ $pantiAsuhan->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($pantiAsuhan->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Galeri Foto --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-images text-primary me-2"></i>Galeri Foto Panti</h6>
                        <a href="{{ route('panti-asuhan.edit', $pantiAsuhan) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah Foto
                        </a>
                    </div>
                    <div class="card-body">
                        @if($pantiAsuhan->fotoPanti->count())
                            <div class="galeri-grid">
                                @foreach($pantiAsuhan->fotoPanti as $idx => $foto)
                                <div class="galeri-item"
                                     data-src="{{ asset('storage/' . $foto->foto) }}"
                                     data-ket="{{ $foto->keterangan ?? '' }}"
                                     data-idx="{{ $idx }}">
                                    <img src="{{ asset('storage/' . $foto->foto) }}" alt="{{ $foto->keterangan }}">
                                    @if($idx === 0)
                                        <span class="cover-badge">Cover</span>
                                    @endif
                                    <div class="galeri-ket">{{ $foto->keterangan ?: 'Foto ' . ($idx+1) }}</div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="galeri-empty">
                                <i class="fas fa-image fa-2x mb-2 d-block"></i>
                                <div class="fw-semibold mb-1">Belum ada foto</div>
                                <a href="{{ route('panti-asuhan.edit', $pantiAsuhan) }}" class="btn btn-primary btn-sm mt-1">
                                    <i class="fas fa-upload me-1"></i> Upload Foto
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Metadata --}}
                <div class="text-muted small text-end">
                    Ditambahkan: {{ $pantiAsuhan->created_at->translatedFormat('d F Y, H:i') }}
                    &nbsp;·&nbsp;
                    Diperbarui: {{ $pantiAsuhan->updated_at->translatedFormat('d F Y, H:i') }}
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox-overlay">
    <button class="lb-close">&times;</button>
    <button class="lb-nav lb-prev"><i class="fas fa-chevron-left"></i></button>
    <img id="lb-img" src="" alt="">
    <div id="lb-ket" class="lb-ket"></div>
    <button class="lb-nav lb-next"><i class="fas fa-chevron-right"></i></button>
</div>
@endsection

@section('scripts')
<script>
// ===== Hapus panti =====
document.getElementById('btn-hapus-detail').addEventListener('click', function () {
    swal({
        title: 'Hapus Panti Asuhan?',
        text: 'Data "{{ $pantiAsuhan->nama_panti }}" beserta semua foto dan data terkait akan dihapus permanen.',
        icon: 'warning',
        buttons: { cancel: 'Batal', confirm: { text: 'Ya, Hapus', className: 'btn-danger' } },
        dangerMode: true,
    }).then(ok => { if (ok) document.getElementById('form-hapus-detail').submit(); });
});

// ===== Lightbox =====
(function () {
    const items   = [...document.querySelectorAll('.galeri-item')];
    const overlay = document.getElementById('lightbox-overlay');
    const img     = document.getElementById('lb-img');
    const ket     = document.getElementById('lb-ket');
    let current   = 0;

    function open(idx) {
        current = idx;
        img.src  = items[idx].dataset.src;
        ket.textContent = items[idx].dataset.ket;
        overlay.classList.add('show');
    }

    function close() { overlay.classList.remove('show'); img.src = ''; }
    function prev()  { open((current - 1 + items.length) % items.length); }
    function next()  { open((current + 1) % items.length); }

    items.forEach((el, i) => el.addEventListener('click', () => open(i)));
    overlay.querySelector('.lb-close').addEventListener('click', close);
    overlay.querySelector('.lb-prev').addEventListener('click', prev);
    overlay.querySelector('.lb-next').addEventListener('click', next);
    overlay.addEventListener('click', e => { if (e.target === overlay) close(); });
    document.addEventListener('keydown', e => {
        if (!overlay.classList.contains('show')) return;
        if (e.key === 'Escape')      close();
        if (e.key === 'ArrowLeft')   prev();
        if (e.key === 'ArrowRight')  next();
    });
})();
</script>
@endsection
