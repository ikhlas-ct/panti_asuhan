<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $panti->nama_panti }} – {{ $setting?->nama ?? 'TitikKebaikan' }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet"/>
  <style>
    /* ══ HERO ══════════════════════════════════════════════ */
    .panti-hero {
      position: relative; height: 420px;
      background: #1c3a2e; overflow: hidden;
    }
    .panti-hero-main-img {
      width: 100%; height: 100%; object-fit: cover; opacity: .5;
      transition: opacity .4s;
    }
    .panti-hero-overlay {
      position: absolute; inset: 0;
      display: flex; flex-direction: column; justify-content: flex-end;
      padding: 2rem 2rem 1.5rem;
      background: linear-gradient(to top, rgba(0,0,0,.75) 0%, transparent 55%);
    }
    .panti-hero-badge {
      display: inline-flex; align-items: center; gap: .4rem;
      background: rgba(255,255,255,.15); backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,.2);
      color: #fff; border-radius: 50px; font-size: .78rem;
      padding: .3rem .85rem; margin-bottom: .75rem; width: fit-content;
    }
    .panti-hero h1 {
      color: #fff; font-size: clamp(1.5rem, 4vw, 2.3rem);
      font-weight: 800; margin: 0 0 .45rem; line-height: 1.2;
    }
    .panti-hero-addr { color: rgba(255,255,255,.8); font-size: .9rem; }
    .panti-hero-counter {
      position: absolute; top: 1rem; right: 1rem;
      background: rgba(0,0,0,.45); backdrop-filter: blur(6px);
      color: #fff; border-radius: 50px; padding: .3rem .75rem;
      font-size: .8rem; font-weight: 600; display: none;
    }

    /* ══ GALERI STRIP ═══════════════════════════════════════ */
    .gallery-strip {
      background: #162e22; padding: .6rem 0; overflow: hidden;
    }
    .gallery-strip-inner {
      display: flex; gap: .5rem; overflow-x: auto;
      padding: 0 1rem; scrollbar-width: none;
    }
    .gallery-strip-inner::-webkit-scrollbar { display: none; }
    .g-thumb {
      flex-shrink: 0; width: 72px; height: 72px;
      object-fit: cover; border-radius: 8px; cursor: pointer;
      border: 2.5px solid transparent;
      transition: border-color .2s, transform .15s;
      opacity: .7;
    }
    .g-thumb:hover { opacity: 1; transform: scale(1.05); }
    .g-thumb.active { border-color: #e87722; opacity: 1; }
    .g-thumb-add {
      flex-shrink: 0; width: 72px; height: 72px; border-radius: 8px;
      background: rgba(255,255,255,.08); border: 2px dashed rgba(255,255,255,.2);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: rgba(255,255,255,.5); font-size: .75rem;
      text-align: center; padding: .3rem; line-height: 1.3;
      transition: background .2s;
    }
    .g-thumb-add:hover { background: rgba(255,255,255,.15); }

    /* ══ LIGHTBOX ════════════════════════════════════════════ */
    .lightbox-overlay {
      display: none; position: fixed; inset: 0; z-index: 9999;
      background: rgba(0,0,0,.92); backdrop-filter: blur(4px);
      flex-direction: column; align-items: center; justify-content: center;
    }
    .lightbox-overlay.open { display: flex; }
    .lightbox-img-wrap {
      position: relative; max-width: 90vw; max-height: 78vh;
      display: flex; align-items: center; justify-content: center;
    }
    .lightbox-img-wrap img {
      max-width: 100%; max-height: 78vh;
      border-radius: 10px; object-fit: contain;
      user-select: none;
    }
    .lb-btn {
      position: absolute; top: 50%; transform: translateY(-50%);
      width: 44px; height: 44px; border-radius: 50%;
      background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2);
      color: #fff; font-size: 1.2rem; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: background .2s; backdrop-filter: blur(4px);
    }
    .lb-btn:hover { background: rgba(255,255,255,.3); }
    .lb-btn.prev { left: -56px; }
    .lb-btn.next { right: -56px; }
    .lightbox-caption {
      margin-top: .75rem; color: rgba(255,255,255,.7);
      font-size: .85rem; text-align: center; max-width: 600px;
    }
    .lightbox-counter {
      color: rgba(255,255,255,.5); font-size: .78rem;
      margin-top: .3rem;
    }
    .lb-strip {
      display: flex; gap: .4rem; margin-top: 1rem;
      overflow-x: auto; max-width: 90vw; padding-bottom: .3rem;
      scrollbar-width: none;
    }
    .lb-strip::-webkit-scrollbar { display: none; }
    .lb-strip-thumb {
      flex-shrink: 0; width: 54px; height: 54px;
      object-fit: cover; border-radius: 6px; cursor: pointer;
      border: 2px solid transparent; opacity: .55;
      transition: opacity .15s, border-color .15s;
    }
    .lb-strip-thumb.active,
    .lb-strip-thumb:hover { opacity: 1; border-color: #e87722; }
    .lb-close {
      position: fixed; top: 1rem; right: 1rem;
      width: 40px; height: 40px; border-radius: 50%;
      background: rgba(255,255,255,.12); border: none;
      color: #fff; font-size: 1.3rem; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      z-index: 10; transition: background .2s;
    }
    .lb-close:hover { background: rgba(255,255,255,.25); }

    /* ══ LAYOUT CARDS ════════════════════════════════════════ */
    .info-card {
      background: #fff; border-radius: 14px;
      border: 1px solid #e8edf0; padding: 1.25rem;
    }
    .info-card-title {
      font-size: .72rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: .08em; color: #999; margin-bottom: .85rem;
    }
    .info-row {
      display: flex; align-items: flex-start; gap: .6rem;
      margin-bottom: .6rem; font-size: .88rem;
    }
    .info-row:last-child { margin-bottom: 0; }
    .info-row i { color: #e87722; margin-top: .15rem; flex-shrink: 0; }
    .info-row-label { color: #888; font-size: .75rem; margin-bottom: .1rem; }

    .stat-pill {
      display: inline-flex; align-items: center; gap: .5rem;
      background: #f3f8f5; border-radius: 10px;
      padding: .55rem .95rem; font-size: .87rem; font-weight: 600;
    }
    .stat-pill i { color: #1c3a2e; }

    /* ══ PENGURUS ════════════════════════════════════════════ */
    .pengurus-card {
      display: flex; align-items: center; gap: .75rem;
      background: #f9fafb; border-radius: 10px; padding: .75rem 1rem;
    }
    .pengurus-avatar {
      width: 44px; height: 44px; border-radius: 50%;
      object-fit: cover; background: #dde;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem; color: #888; flex-shrink: 0; overflow: hidden;
    }
    .pengurus-name  { font-weight: 600; font-size: .88rem; }
    .pengurus-meta  { font-size: .76rem; color: #888; }

    /* ══ ANAK ASUH ═══════════════════════════════════════════ */
    .anak-row {
      display: flex; align-items: center; gap: .75rem;
      padding: .5rem 0; border-bottom: 1px solid #f0f0f0;
    }
    .anak-row:last-child { border-bottom: none; }
    .anak-avatar {
      width: 34px; height: 34px; border-radius: 50%;
      background: #e8f0eb; display: flex; align-items: center;
      justify-content: center; flex-shrink: 0; font-size: .9rem; overflow: hidden;
    }
    .anak-name { font-weight: 600; font-size: .85rem; }
    .anak-meta { font-size: .74rem; color: #888; }

    /* ══ KEGIATAN ════════════════════════════════════════════ */
    .kegiatan-item {
      border-left: 3px solid #1c3a2e; padding-left: 1rem; margin-bottom: 1rem;
    }
    .kegiatan-item:last-child { margin-bottom: 0; }

    /* ══ SIDEBAR DONASI ══════════════════════════════════════ */
    .donasi-cta-box {
      background: linear-gradient(135deg,#1c3a2e,#2d5a44);
      border-radius: 16px; padding: 1.5rem; color: #fff;
      position: sticky; top: 90px;
    }
    .donasi-cta-box h5 { font-weight: 700; margin-bottom: .4rem; }
    .donasi-cta-box p  { font-size: .84rem; opacity: .82; margin-bottom: 1rem; }
    .btn-donasi-lg {
      display: block; width: 100%; padding: .85rem;
      background: #e87722; color: #fff; border: none;
      border-radius: 10px; font-weight: 700; font-size: 1rem;
      text-align: center; cursor: pointer; text-decoration: none;
      transition: opacity .2s;
    }
    .btn-donasi-lg:hover { opacity: .88; color: #fff; }

    .btn-back {
      display: inline-flex; align-items: center; gap: .4rem;
      color: #1c3a2e; font-size: .88rem; font-weight: 600;
      text-decoration: none; padding: .4rem 0; margin-bottom: 1rem;
    }
    .btn-back:hover { color: #e87722; }

    /* Keyboard hint */
    @media (max-width: 640px) {
      .lb-btn.prev { left: -48px; }
      .lb-btn.next { right: -48px; }
    }
  </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">
      <div class="brand-icon"><i class="bi bi-heart-fill"></i></div>{{ $setting?->nama ?? 'TitikKebaikan' }}
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('berita') }}">Berita</a></li>
        <li class="nav-item"><a class="nav-link active" href="{{ route('daftar-panti') }}">Daftar Panti</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('kerjasama') }}">Kerjasama Kami</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('tentang') }}">Tentang Kami</a></li>
      </ul>
      <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
        @auth
          <a href="{{ route('donasi.index') }}" class="btn-bookmark"><i class="bi bi-heart"></i> Donasi Saya</a>
          <span class="btn-admin">{{ Auth::user()->username }}</span>
        @else
          <a href="{{ route('login') }}" class="btn-bookmark"><i class="bi bi-person"></i> Masuk</a>
        @endauth
      </div>
    </div>
  </div>
</nav>

{{-- ══ HERO FOTO UTAMA ═══════════════════════════════════════════════════ --}}
<div class="panti-hero">
  @if($panti->fotoPanti->isNotEmpty())
    <img id="heroImg"
         src="{{ asset('storage/'.$panti->fotoPanti->first()->foto) }}"
         alt="{{ $panti->nama_panti }}"
         class="panti-hero-main-img"/>
    @if($panti->fotoPanti->count() > 1)
      <div class="panti-hero-counter" id="heroCounter">
        <i class="bi bi-images me-1"></i>
        <span id="heroIdx">1</span> / {{ $panti->fotoPanti->count() }}
      </div>
    @endif
  @else
    <div style="width:100%;height:100%;background:linear-gradient(135deg,#1c3a2e,#2d5a44);"></div>
  @endif
  <div class="panti-hero-overlay">
    <div class="panti-hero-badge">
      <i class="bi bi-house-heart-fill"></i> Panti Asuhan
    </div>
    <h1>{{ $panti->nama_panti }}</h1>
    <div class="panti-hero-addr">
      <i class="bi bi-geo-alt-fill" style="color:#e87722;"></i>
      {{ $panti->alamat }}{{ $panti->kecamatan ? ', Kec. '.$panti->kecamatan : '' }}{{ $panti->kelurahan ? ', Kel. '.$panti->kelurahan : '' }}
    </div>
  </div>
</div>

{{-- ══ GALLERY STRIP (semua foto, scroll horizontal) ═════════════════════ --}}
@if($panti->fotoPanti->isNotEmpty())
<div class="gallery-strip">
  <div class="gallery-strip-inner" id="stripInner">
    @foreach($panti->fotoPanti as $i => $foto)
    <img src="{{ asset('storage/'.$foto->foto) }}"
         class="g-thumb {{ $i === 0 ? 'active' : '' }}"
         data-idx="{{ $i }}"
         data-url="{{ asset('storage/'.$foto->foto) }}"
         data-caption="{{ $foto->keterangan ?? '' }}"
         alt="{{ $foto->keterangan ?? 'Foto '.($i+1) }}"
         onclick="stripClick(this)"
         title="{{ $foto->keterangan ?? 'Foto '.($i+1) }}"/>
    @endforeach
    {{-- Tombol buka semua (lightbox) --}}
    @if($panti->fotoPanti->count() > 1)
    <div class="g-thumb-add" onclick="openLightbox(0)" title="Lihat semua foto">
      <div>
        <i class="bi bi-grid-3x3-gap-fill" style="font-size:1.1rem;display:block;margin-bottom:.2rem;"></i>
        Semua<br>Foto
      </div>
    </div>
    @endif
  </div>
</div>
@endif

{{-- ══ LIGHTBOX ════════════════════════════════════════════════════════════ --}}
<div class="lightbox-overlay" id="lightbox">
  <button class="lb-close" onclick="closeLightbox()"><i class="bi bi-x-lg"></i></button>
  <div class="lightbox-img-wrap">
    <button class="lb-btn prev" id="lbPrev" onclick="lbNav(-1)"><i class="bi bi-chevron-left"></i></button>
    <img id="lbImg" src="" alt=""/>
    <button class="lb-btn next" id="lbNext" onclick="lbNav(1)"><i class="bi bi-chevron-right"></i></button>
  </div>
  <div class="lightbox-caption" id="lbCaption"></div>
  <div class="lightbox-counter" id="lbCounter"></div>
  <div class="lb-strip" id="lbStrip"></div>
</div>

{{-- ══ MAIN CONTENT ════════════════════════════════════════════════════════ --}}
<section class="py-4">
  <div class="container">
    <a href="{{ route('daftar-panti') }}" class="btn-back">
      <i class="bi bi-arrow-left"></i> Kembali ke Daftar Panti
    </a>

    <div class="row g-4">

      {{-- ── KOLOM KIRI ────────────────────────────────────────── --}}
      <div class="col-lg-8">

        {{-- Stat pills --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
          <div class="stat-pill">
            <i class="bi bi-people-fill"></i>
            <span>{{ $panti->anak_asuh_count }} Anak Asuh</span>
          </div>
          @if($panti->kecamatan)
          <div class="stat-pill">
            <i class="bi bi-geo-alt-fill"></i>
            <span>Kec. {{ $panti->kecamatan }}</span>
          </div>
          @endif
          @if($panti->pengurus && $panti->pengurus->count())
          <div class="stat-pill">
            <i class="bi bi-person-badge-fill"></i>
            <span>{{ $panti->pengurus->count() }} Pengurus</span>
          </div>
          @endif
          @if($panti->fotoPanti->count())
          <div class="stat-pill" style="cursor:pointer;" onclick="openLightbox(0)">
            <i class="bi bi-images"></i>
            <span>{{ $panti->fotoPanti->count() }} Foto</span>
          </div>
          @endif
          <div class="stat-pill">
            <i class="bi bi-check-circle-fill" style="color:#2ecc71;"></i>
            <span>Aktif</span>
          </div>
        </div>

        {{-- Informasi Panti --}}
        <div class="info-card mb-4">
          <div class="info-card-title"><i class="bi bi-info-circle me-1"></i>Informasi Panti</div>
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="info-row-label">Nama Panti</div>
              <div class="info-row">
                <i class="bi bi-house-heart-fill"></i>
                <span>{{ $panti->nama_panti }}</span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-row-label">Nama Kontak</div>
              <div class="info-row">
                <i class="bi bi-person-fill"></i>
                <span>{{ $panti->nama_kontak ?? '-' }}</span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-row-label">Telepon</div>
              <div class="info-row">
                <i class="bi bi-telephone-fill"></i>
                @if($panti->no_telp)
                  <a href="tel:{{ $panti->no_telp }}" style="color:inherit;">{{ $panti->no_telp }}</a>
                @else
                  <span>-</span>
                @endif
              </div>
            </div>
            <div class="col-sm-6">
              <div class="info-row-label">Email</div>
              <div class="info-row">
                <i class="bi bi-envelope-fill"></i>
                @if($panti->email)
                  <a href="mailto:{{ $panti->email }}" style="color:inherit;">{{ $panti->email }}</a>
                @else
                  <span>-</span>
                @endif
              </div>
            </div>
            <div class="col-12">
              <div class="info-row-label">Alamat Lengkap</div>
              <div class="info-row">
                <i class="bi bi-geo-alt-fill"></i>
                <span>
                  {{ $panti->alamat }}
                  {{ $panti->kelurahan ? ', Kel. '.$panti->kelurahan : '' }}
                  {{ $panti->kecamatan ? ', Kec. '.$panti->kecamatan : '' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        {{-- Tentang Panti --}}
        @if($panti->keterangan)
        <div class="info-card mb-4">
          <div class="info-card-title"><i class="bi bi-file-text me-1"></i>Tentang Panti</div>
          <p style="font-size:.9rem;line-height:1.8;color:#444;margin:0;">{{ $panti->keterangan }}</p>
        </div>
        @endif

        {{-- Pengurus --}}
        @if($panti->pengurus && $panti->pengurus->count())
        <div class="info-card mb-4">
          <div class="info-card-title"><i class="bi bi-person-badge me-1"></i>Pengurus Panti</div>
          <div class="d-flex flex-column gap-2">
            @foreach($panti->pengurus as $pr)
            <div class="pengurus-card">
              @if($pr->foto)
                <img src="{{ asset('storage/'.$pr->foto) }}" class="pengurus-avatar" alt="{{ $pr->nama }}"/>
              @else
                <div class="pengurus-avatar"><i class="bi bi-person-fill"></i></div>
              @endif
              <div>
                <div class="pengurus-name">{{ $pr->nama }}</div>
                <div class="pengurus-meta">
                  {{ $pr->jabatan ?? 'Pengurus' }}
                  @if($pr->pendidikan_terakhir) · {{ $pr->pendidikan_terakhir }} @endif
                  @if($pr->no_telp)
                    · <a href="tel:{{ $pr->no_telp }}" style="color:#888;">{{ $pr->no_telp }}</a>
                  @endif
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Anak Asuh --}}
        @if($panti->anakAsuh && $panti->anakAsuh->count())
        <div class="info-card mb-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="info-card-title mb-0"><i class="bi bi-people me-1"></i>Anak Asuh</div>
            <span class="badge" style="background:#e8f5ee;color:#1c3a2e;font-weight:700;font-size:.8rem;">
              {{ $panti->anak_asuh_count }} total
            </span>
          </div>
          @foreach($panti->anakAsuh->take(10) as $anak)
          <div class="anak-row">
            <div class="anak-avatar">
              @if($anak->foto)
                <img src="{{ asset('storage/'.$anak->foto) }}" style="width:100%;height:100%;object-fit:cover;" alt="{{ $anak->nama }}"/>
              @else
                {{ $anak->jenis_kelamin === 'L' ? '👦' : '👧' }}
              @endif
            </div>
            <div class="flex-grow-1">
              <div class="anak-name">{{ $anak->nama }}</div>
              <div class="anak-meta">
                {{ $anak->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                @if($anak->tanggal_lahir) · {{ $anak->usia }} tahun @endif
                @if($anak->jenjang_pendidikan) · {{ $anak->jenjang_pendidikan }} @endif
                @if($anak->status_yatim)
                  · <span style="background:#fff3e0;color:#e65100;padding:.05rem .35rem;border-radius:4px;font-size:.68rem;font-weight:600;">
                      {{ str_replace('_',' ', ucfirst($anak->status_yatim)) }}
                    </span>
                @endif
              </div>
            </div>
            <span class="badge" style="background:#e8f5ee;color:#1c3a2e;font-size:.68rem;flex-shrink:0;">
              {{ $anak->jenis_tinggal === 'dalam' ? 'Dalam' : 'Luar' }}
            </span>
          </div>
          @endforeach
          @if($panti->anak_asuh_count > 10)
          <div class="text-center pt-3" style="font-size:.82rem;color:#aaa;">
            + {{ $panti->anak_asuh_count - 10 }} anak asuh lainnya tidak ditampilkan
          </div>
          @endif
        </div>
        @endif

        {{-- Kegiatan --}}
        @if($panti->konten && $panti->konten->count())
        <div class="info-card mb-4">
          <div class="info-card-title"><i class="bi bi-calendar-event me-1"></i>Kegiatan Terkini</div>
          @foreach($panti->konten as $k)
          <div class="kegiatan-item">
            <div class="d-flex align-items-start gap-2 flex-wrap mb-1">
              <span class="badge rounded-pill
                @if($k->status==='berlangsung') bg-success
                @elseif($k->status==='direncanakan') bg-primary
                @else bg-secondary @endif"
                style="font-size:.68rem;">
                {{ ucfirst($k->status) }}
              </span>
              <strong style="font-size:.88rem;">{{ $k->judul }}</strong>
            </div>
            @if($k->tanggal_mulai)
            <div style="font-size:.78rem;color:#888;">
              <i class="bi bi-calendar3"></i>
              {{ $k->tanggal_mulai->format('d M Y') }}
              @if($k->tanggal_selesai && $k->tanggal_selesai->ne($k->tanggal_mulai))
                – {{ $k->tanggal_selesai->format('d M Y') }}
              @endif
              @if($k->lokasi) · <i class="bi bi-geo-alt"></i> {{ $k->lokasi }} @endif
            </div>
            @endif
            @if($k->ringkasan)
            <p style="font-size:.82rem;color:#555;margin:.3rem 0 0;">{{ Str::limit($k->ringkasan, 130) }}</p>
            @endif
          </div>
          @endforeach
        </div>
        @endif

      </div>{{-- /col-lg-8 --}}

      {{-- ── SIDEBAR KANAN ──────────────────────────────────────── --}}
      <div class="col-lg-4">

        {{-- CTA Donasi --}}
        <div class="donasi-cta-box">
          <h5><i class="bi bi-heart-fill me-2" style="color:#e87722;"></i>Bantu Mereka</h5>
          <p>Donasi Anda, berapapun jumlahnya, sangat berarti bagi <strong>{{ $panti->anak_asuh_count }} anak asuh</strong> di panti ini.</p>

          @auth
            @if(Auth::user()->role === 'donatur' && Auth::user()->donatur)
              <a href="{{ route('donasi.create', ['panti_asuhan_id' => $panti->id]) }}" class="btn-donasi-lg mb-2">
                <i class="bi bi-heart-fill me-2"></i>Donasi Sekarang
              </a>
              <a href="{{ route('donasi.index') }}" class="d-block text-center py-2"
                 style="color:rgba(255,255,255,.65);font-size:.83rem;text-decoration:none;">
                Lihat riwayat donasi saya
              </a>
            @elseif(in_array(Auth::user()->role, ['admin_dinsos','admin_panti']))
              <a href="{{ route('donasi.create', ['panti_asuhan_id' => $panti->id]) }}" class="btn-donasi-lg">
                <i class="bi bi-plus-circle me-2"></i>Tambah Donasi
              </a>
            @else
              <button class="btn-donasi-lg" onclick="showLoginModal()">
                <i class="bi bi-heart-fill me-2"></i>Donasi Sekarang
              </button>
            @endif
          @else
            <button class="btn-donasi-lg" onclick="showLoginModal()">
              <i class="bi bi-heart-fill me-2"></i>Donasi Sekarang
            </button>
            <p class="text-center mt-2 mb-0" style="font-size:.77rem;opacity:.6;">
              Perlu masuk terlebih dahulu
            </p>
          @endauth
        </div>

        {{-- Kontak sidebar --}}
        <div class="info-card mt-3">
          <div class="info-card-title"><i class="bi bi-telephone me-1"></i>Hubungi Panti</div>
          @if($panti->no_telp)
          <div class="info-row">
            <i class="bi bi-telephone-fill"></i>
            <div>
              <div class="info-row-label">Telepon</div>
              <a href="tel:{{ $panti->no_telp }}" style="color:#1c3a2e;font-weight:600;">{{ $panti->no_telp }}</a>
            </div>
          </div>
          @endif
          @if($panti->email)
          <div class="info-row">
            <i class="bi bi-envelope-fill"></i>
            <div>
              <div class="info-row-label">Email</div>
              <a href="mailto:{{ $panti->email }}" style="color:#1c3a2e;font-weight:600;">{{ $panti->email }}</a>
            </div>
          </div>
          @endif
          @if($panti->nama_kontak)
          <div class="info-row">
            <i class="bi bi-person-fill"></i>
            <div>
              <div class="info-row-label">Narahubung</div>
              <span style="font-weight:600;">{{ $panti->nama_kontak }}</span>
            </div>
          </div>
          @endif
          @if(!$panti->no_telp && !$panti->email && !$panti->nama_kontak)
            <p style="color:#bbb;font-size:.85rem;margin:0;">Kontak belum tersedia.</p>
          @endif
        </div>

        {{-- Share --}}
        <div class="info-card mt-3 text-center">
          <div class="info-card-title"><i class="bi bi-share me-1"></i>Bagikan Panti Ini</div>
          <div class="d-flex justify-content-center gap-2">
            <button onclick="shareDetail()" class="btn btn-sm fw-600"
                    style="background:#1c3a2e;color:#fff;border-radius:8px;padding:.5rem 1.1rem;">
              <i class="bi bi-share-fill me-1"></i>Salin Link
            </button>
            <a href="https://wa.me/?text={{ urlencode($panti->nama_panti.' – '.\Illuminate\Support\Facades\URL::current()) }}"
               target="_blank" rel="noopener"
               class="btn btn-sm"
               style="background:#25d366;color:#fff;border-radius:8px;padding:.5rem 1rem;">
              <i class="bi bi-whatsapp"></i>
            </a>
          </div>
        </div>

      </div>{{-- /sidebar --}}
    </div>{{-- /row --}}
  </div>
</section>

{{-- MODAL: Perlu Login --}}
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">
      <div class="modal-header border-0 pb-0"
           style="background:linear-gradient(135deg,#1c3a2e,#2d5a44);color:#fff;padding:1.5rem 1.5rem 1rem;">
        <div>
          <div style="width:44px;height:44px;background:rgba(255,255,255,.15);border-radius:12px;
                      display:flex;align-items:center;justify-content:center;margin-bottom:.75rem;">
            <i class="bi bi-heart-fill" style="font-size:1.2rem;"></i>
          </div>
          <h5 class="modal-title mb-1 fw-700">Masuk untuk Berdonasi</h5>
          <p class="mb-0" style="font-size:.85rem;opacity:.8;">
            Donasi ke <strong>{{ $panti->nama_panti }}</strong>
          </p>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <p style="font-size:.9rem;color:#555;line-height:1.65;">
          Untuk berdonasi, Anda perlu masuk ke akun donatur terlebih dahulu. Setelah masuk, Anda akan langsung diarahkan ke formulir donasi untuk panti ini.
        </p>
        <div class="d-grid gap-2 mt-3">
          <a href="{{ route('login') }}?redirect={{ urlencode(route('donasi.create').'?panti_asuhan_id='.$panti->id) }}"
             class="btn btn-lg fw-600"
             style="background:#1c3a2e;color:#fff;border-radius:10px;">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
          </a>
          @if(Route::has('register'))
          <a href="{{ route('register') }}" class="btn btn-lg fw-500"
             style="border:2px solid #1c3a2e;color:#1c3a2e;border-radius:10px;">
            <i class="bi bi-person-plus me-2"></i>Daftar Akun Baru
          </a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@include('pages.landing.partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('landing/js/shared.js') }}"></script>
<script>
// ── Data foto dari Blade ─────────────────────────────────────
const photos = [
  @foreach($panti->fotoPanti as $foto)
  {
    url:     '{{ asset('storage/'.$foto->foto) }}',
    caption: '{{ addslashes($foto->keterangan ?? '') }}',
  },
  @endforeach
];

let lbCurrent = 0;

// ══ STRIP: klik thumbnail → ganti hero ══════════════════════
function stripClick(el) {
  const idx = parseInt(el.dataset.idx);
  setHero(idx);
}

function setHero(idx) {
  if (!photos.length) return;
  lbCurrent = idx;
  const p = photos[idx];

  // Ganti hero
  const heroImg = document.getElementById('heroImg');
  if (heroImg) {
    heroImg.style.opacity = '.2';
    heroImg.src = p.url;
    heroImg.onload = () => { heroImg.style.opacity = '.5'; };
  }

  // Counter hero
  const counter = document.getElementById('heroCounter');
  const heroIdx = document.getElementById('heroIdx');
  if (counter) { counter.style.display = 'block'; heroIdx.textContent = idx + 1; }

  // Active strip
  document.querySelectorAll('.g-thumb').forEach(t => {
    t.classList.toggle('active', parseInt(t.dataset.idx) === idx);
  });

  // Scroll strip ke thumb yang aktif
  const strip = document.getElementById('stripInner');
  const active = strip?.querySelector('.g-thumb.active');
  if (active && strip) {
    strip.scrollTo({ left: active.offsetLeft - 80, behavior: 'smooth' });
  }
}

// ══ LIGHTBOX ════════════════════════════════════════════════
function openLightbox(startIdx) {
  lbCurrent = startIdx ?? 0;
  buildLbStrip();
  renderLb();
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeLightbox() {
  document.getElementById('lightbox').classList.remove('open');
  document.body.style.overflow = '';
}

function renderLb() {
  if (!photos.length) return;
  const p = photos[lbCurrent];
  document.getElementById('lbImg').src = p.url;
  document.getElementById('lbCaption').textContent = p.caption;
  document.getElementById('lbCounter').textContent = `${lbCurrent + 1} / ${photos.length}`;

  // Prev / Next visibility
  document.getElementById('lbPrev').style.display = photos.length > 1 ? '' : 'none';
  document.getElementById('lbNext').style.display = photos.length > 1 ? '' : 'none';

  // Aktif strip lightbox
  document.querySelectorAll('.lb-strip-thumb').forEach((t, i) => {
    t.classList.toggle('active', i === lbCurrent);
  });

  // Scroll lb strip
  const lbStrip = document.getElementById('lbStrip');
  const lbActive = lbStrip?.querySelector('.lb-strip-thumb.active');
  if (lbActive && lbStrip) {
    lbStrip.scrollTo({ left: lbActive.offsetLeft - 100, behavior: 'smooth' });
  }
}

function buildLbStrip() {
  const strip = document.getElementById('lbStrip');
  if (photos.length <= 1) { strip.style.display = 'none'; return; }
  strip.innerHTML = '';
  photos.forEach((p, i) => {
    const img = document.createElement('img');
    img.src = p.url;
    img.className = 'lb-strip-thumb' + (i === lbCurrent ? ' active' : '');
    img.alt = p.caption || `Foto ${i + 1}`;
    img.onclick = () => { lbCurrent = i; renderLb(); };
    strip.appendChild(img);
  });
}

function lbNav(dir) {
  lbCurrent = (lbCurrent + dir + photos.length) % photos.length;
  renderLb();
}

// Keyboard: arrow key & Escape
document.addEventListener('keydown', e => {
  const lb = document.getElementById('lightbox');
  if (!lb.classList.contains('open')) return;
  if (e.key === 'ArrowLeft')  lbNav(-1);
  if (e.key === 'ArrowRight') lbNav(1);
  if (e.key === 'Escape')     closeLightbox();
});

// Klik backdrop lightbox → tutup
document.getElementById('lightbox').addEventListener('click', e => {
  if (e.target === document.getElementById('lightbox')) closeLightbox();
});

// Touch swipe lightbox
let tsX = null;
document.getElementById('lightbox').addEventListener('touchstart', e => { tsX = e.touches[0].clientX; });
document.getElementById('lightbox').addEventListener('touchend', e => {
  if (tsX === null) return;
  const dx = e.changedTouches[0].clientX - tsX;
  if (Math.abs(dx) > 50) lbNav(dx < 0 ? 1 : -1);
  tsX = null;
});

// ══ MODAL LOGIN ══════════════════════════════════════════════
function showLoginModal() {
  new bootstrap.Modal(document.getElementById('loginModal')).show();
}

// ══ SHARE ═══════════════════════════════════════════════════
function shareDetail() {
  const url   = window.location.href;
  const title = '{{ addslashes($panti->nama_panti) }}';
  if (navigator.share) {
    navigator.share({ title, text: `Yuk bantu ${title}!`, url });
  } else {
    navigator.clipboard.writeText(url).then(() => {
      // Feedback singkat
      const btn = event.currentTarget;
      const ori = btn.innerHTML;
      btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Tersalin!';
      btn.style.background = '#2ecc71';
      setTimeout(() => { btn.innerHTML = ori; btn.style.background = '#1c3a2e'; }, 1800);
    });
  }
}

// Init: tampilkan counter jika lebih dari 1 foto
if (photos.length > 1) {
  const counter = document.getElementById('heroCounter');
  if (counter) counter.style.display = 'block';
}
</script>
</body>
</html>
