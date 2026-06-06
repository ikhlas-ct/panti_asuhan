<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $konten->judul ?? 'Detail Berita' }} – {{ $setting?->nama ?? 'TitikKebaikan' }}</title>
  <meta name="description" content="{{ $konten->ringkasan ? Str::limit(strip_tags($konten->ringkasan), 160) : Str::limit(strip_tags($konten->isi ?? ''), 160) }}"/>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet"/>

  <style>
    /* ── BREADCRUMB ─────────────────────────────────────── */
    .breadcrumb-bar {
      background: var(--white);
      border-bottom: 1px solid rgba(0,0,0,.06);
      padding: 10px 0;
      font-size: .82rem;
      color: var(--text-muted);
    }
    .breadcrumb-bar a {
      color: var(--green-dark);
      text-decoration: none;
      font-weight: 500;
    }
    .breadcrumb-bar a:hover { text-decoration: underline; }
    .breadcrumb-sep { margin: 0 6px; opacity: .4; }

    /* ── HERO ARTIKEL ────────────────────────────────────── */
    .hero-detail {
      background: linear-gradient(135deg, var(--green-dark) 0%, #1a5c3a 60%, #2d7a54 100%);
      padding: 56px 0 40px;
      position: relative;
      overflow: hidden;
    }
    .hero-detail::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='15'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .hero-detail .deco-circle {
      position: absolute;
      border-radius: 50%;
      background: rgba(255,255,255,.05);
      pointer-events: none;
    }
    .hero-meta-row {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 16px;
      margin-bottom: 18px;
    }
    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: rgba(255,255,255,.15);
      backdrop-filter: blur(6px);
      border: 1px solid rgba(255,255,255,.25);
      color: #fff;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: .78rem;
      font-weight: 600;
    }
    .hero-badge.orange { background: var(--orange); border-color: transparent; }
    .hero-title-detail {
      color: #fff;
      font-size: clamp(1.5rem, 4vw, 2.3rem);
      font-weight: 800;
      line-height: 1.25;
      margin-bottom: 20px;
      letter-spacing: -.5px;
    }
    .hero-info-row {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 20px;
      color: rgba(255,255,255,.75);
      font-size: .82rem;
    }
    .hero-info-row i { margin-right: 4px; }
    .hero-info-row .sep { opacity: .35; }

    /* ── LAYOUT KONTEN DETAIL ────────────────────────────── */
    .detail-layout { padding: 48px 0 64px; background: var(--cream); }

    /* ── ARTIKEL BODY ────────────────────────────────────── */
    .article-card {
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }
    .article-cover {
      width: 100%;
      max-height: 460px;
      object-fit: cover;
      display: block;
    }
    .article-cover-ph {
      width: 100%;
      height: 300px;
      background: var(--green-pale);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 4rem;
      color: var(--green-dark);
    }
    .article-body { padding: 36px 40px 40px; }
    @media(max-width:576px) { .article-body { padding: 24px 20px 28px; } }

    /* ── AUTHOR BAR ──────────────────────────────────────── */
    .author-bar {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 16px 20px;
      background: var(--cream);
      border-radius: 12px;
      margin-bottom: 28px;
    }
    .author-ava {
      width: 46px; height: 46px;
      background: var(--green-pale);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem;
      color: var(--green-dark);
      flex-shrink: 0;
    }
    .author-name { font-weight: 700; font-size: .88rem; color: var(--text-dark); }
    .author-sub  { font-size: .75rem; color: var(--text-muted); margin-top: 1px; }
    .stat-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: var(--white);
      border: 1px solid rgba(0,0,0,.07);
      padding: 4px 10px;
      border-radius: 20px;
      font-size: .75rem;
      color: var(--text-muted);
    }

    /* ── ISI KONTEN (Summernote HTML) ────────────────────── */
    .konten-isi {
      font-size: 1.02rem;
      line-height: 1.85;
      color: #2d2d2d;
    }
    .konten-isi h1,.konten-isi h2,.konten-isi h3,
    .konten-isi h4,.konten-isi h5,.konten-isi h6 {
      font-weight: 700;
      color: var(--text-dark);
      margin-top: 2rem;
      margin-bottom: .75rem;
    }
    .konten-isi h2 { font-size: 1.4rem; }
    .konten-isi h3 { font-size: 1.2rem; }
    .konten-isi p  { margin-bottom: 1.2rem; }
    .konten-isi img {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
      margin: 12px 0;
      box-shadow: 0 4px 18px rgba(0,0,0,.09);
    }
    .konten-isi blockquote {
      border-left: 4px solid var(--green-dark);
      background: var(--green-pale);
      padding: 14px 20px;
      border-radius: 0 10px 10px 0;
      margin: 24px 0;
      font-style: italic;
      color: var(--green-dark);
    }
    .konten-isi ul, .konten-isi ol {
      padding-left: 24px;
      margin-bottom: 1.2rem;
    }
    .konten-isi li { margin-bottom: .4rem; }
    .konten-isi a  { color: var(--green-dark); }
    .konten-isi table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1.2rem;
      font-size: .9rem;
    }
    .konten-isi table th, .konten-isi table td {
      border: 1px solid #ddd;
      padding: 8px 12px;
    }
    .konten-isi table th { background: var(--green-pale); font-weight: 700; }

    /* ── DIVIDER ─────────────────────────────────────────── */
    .konten-divider {
      border: none;
      border-top: 2px dashed rgba(0,0,0,.08);
      margin: 32px 0;
    }

    /* ── SHARE BAR ───────────────────────────────────────── */
    .share-bar {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
      padding: 20px;
      background: var(--green-pale);
      border-radius: 12px;
    }
    .share-label { font-size: .82rem; font-weight: 700; color: var(--green-dark); }
    .share-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 14px;
      border-radius: 8px;
      border: none;
      font-size: .8rem;
      font-weight: 600;
      cursor: pointer;
      transition: opacity .2s;
      text-decoration: none;
    }
    .share-btn:hover { opacity: .85; }
    .share-wa   { background: #25d366; color: #fff; }
    .share-fb   { background: #1877f2; color: #fff; }
    .share-tw   { background: #000; color: #fff; }
    .share-copy { background: var(--white); color: var(--text-dark); border: 1px solid rgba(0,0,0,.1); }

    /* ── TAGS ────────────────────────────────────────────── */
    .tag-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: var(--white);
      border: 1px solid rgba(0,0,0,.1);
      color: var(--text-dark);
      padding: 4px 12px;
      border-radius: 20px;
      font-size: .78rem;
      font-weight: 500;
      text-decoration: none;
    }
    .tag-pill:hover { background: var(--green-pale); color: var(--green-dark); }

    /* ── SIDEBAR ─────────────────────────────────────────── */
    .sidebar-card {
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 24px;
      margin-bottom: 24px;
    }
    .sidebar-title {
      font-size: .88rem;
      font-weight: 800;
      color: var(--text-dark);
      text-transform: uppercase;
      letter-spacing: .5px;
      margin-bottom: 16px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--green-pale);
    }

    /* ── TERKAIT ITEM ────────────────────────────────────── */
    .related-item {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      padding: 12px 0;
      border-bottom: 1px solid rgba(0,0,0,.05);
      text-decoration: none;
    }
    .related-item:last-child { border-bottom: none; padding-bottom: 0; }
    .related-item:hover .related-title { color: var(--green-dark); }
    .related-thumb {
      width: 72px; height: 72px;
      border-radius: 10px;
      object-fit: cover;
      flex-shrink: 0;
      background: var(--green-pale);
    }
    .related-thumb-ph {
      width: 72px; height: 72px;
      border-radius: 10px;
      flex-shrink: 0;
      background: var(--green-pale);
      display: flex; align-items: center; justify-content: center;
      color: var(--green-dark);
      font-size: 1.4rem;
    }
    .related-title {
      font-size: .84rem;
      font-weight: 700;
      color: var(--text-dark);
      line-height: 1.35;
      transition: color .2s;
    }
    .related-date { font-size: .72rem; color: var(--text-muted); margin-top: 4px; }

    /* ── BOTTOM NAV ──────────────────────────────────────── */
    .nav-articles {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-top: 32px;
    }
    @media(max-width:576px) { .nav-articles { grid-template-columns: 1fr; } }
    .nav-art-btn {
      background: var(--white);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 16px 20px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      text-decoration: none;
      transition: box-shadow .2s, transform .2s;
    }
    .nav-art-btn:hover { box-shadow: 0 8px 28px rgba(0,0,0,.12); transform: translateY(-2px); }
    .nav-art-btn .nav-dir { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--text-muted); margin-bottom: 4px; }
    .nav-art-btn .nav-jtitle { font-size: .85rem; font-weight: 700; color: var(--text-dark); line-height: 1.3; }
    .nav-art-btn.prev { flex-direction: row; }
    .nav-art-btn.next { flex-direction: row-reverse; text-align: right; }

    /* ── BACK TO TOP ─────────────────────────────────────── */
    #backTop {
      position: fixed;
      bottom: 28px; right: 28px;
      width: 44px; height: 44px;
      background: var(--green-dark);
      color: #fff;
      border: none;
      border-radius: 50%;
      display: none;
      align-items: center; justify-content: center;
      cursor: pointer;
      box-shadow: 0 4px 16px rgba(0,0,0,.2);
      z-index: 999;
      transition: opacity .2s;
    }
    #backTop.show { display: flex; }
  </style>
</head>
<body>

@include('pages.landing.partials.navbar')

{{-- ══ BREADCRUMB ══ --}}
<div class="breadcrumb-bar">
  <div class="container d-flex align-items-center flex-wrap gap-1">
    <a href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i>Beranda</a>
    <span class="breadcrumb-sep">/</span>
    @if($jenis === 'kegiatan')
      <a href="{{ route('kerjasama') }}">Kegiatan</a>
    @else
      <a href="{{ route('berita') }}">Berita</a>
    @endif
    @if($konten->kategori)
      <span class="breadcrumb-sep">/</span>
      <a href="{{ route('berita', ['kategori' => $konten->id_kategori]) }}">
        {{ $konten->kategori->nama_kategori }}
      </a>
    @endif
    <span class="breadcrumb-sep">/</span>
    <span style="color:var(--text-dark);font-weight:500;">{{ Str::limit($konten->judul, 50) }}</span>
  </div>
</div>

{{-- ══ HERO DETAIL ══ --}}
<section class="hero-detail">
  <div class="deco-circle" style="width:240px;height:240px;top:-80px;right:-60px;"></div>
  <div class="deco-circle" style="width:100px;height:100px;bottom:20px;left:60px;"></div>
  <div class="container position-relative">

    {{-- Badges --}}
    <div class="hero-meta-row fade-up">
      @if($konten->kategori)
        <span class="hero-badge orange">
          @if($konten->kategori->icon)<i class="{{ $konten->kategori->icon }}"></i>@endif
          {{ $konten->kategori->nama_kategori }}
        </span>
      @endif
      @if($jenis === 'kegiatan')
        <span class="hero-badge">
          <i class="bi bi-calendar-event"></i> Kegiatan
        </span>
        @if($konten->status)
          @php
            $statusStyle = match($konten->status) {
              'berlangsung' => 'background:rgba(255,193,7,.25);border-color:rgba(255,193,7,.4);',
              'selesai'     => 'background:rgba(25,135,84,.2);border-color:rgba(25,135,84,.4);',
              'dibatalkan'  => 'background:rgba(220,53,69,.2);border-color:rgba(220,53,69,.4);',
              default       => 'background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.25);',
            };
            $statusIcon = match($konten->status) {
              'berlangsung' => 'bi-circle-fill',
              'selesai'     => 'bi-check-circle-fill',
              'dibatalkan'  => 'bi-x-circle-fill',
              default       => 'bi-clock-fill',
            };
          @endphp
          <span class="hero-badge" style="{{ $statusStyle }}">
            <i class="bi {{ $statusIcon }}" style="font-size:.5rem;"></i>
            {{ ucfirst($konten->status) }}
          </span>
        @endif
      @else
        <span class="hero-badge">
          <i class="bi bi-newspaper"></i> Berita
        </span>
      @endif
    </div>

    {{-- Judul --}}
    <h1 class="hero-title-detail fade-up delay-1">{{ $konten->judul ?? ($jenis === 'kegiatan' ? 'Judul Kegiatan' : 'Judul Berita') }}</h1>

    {{-- Info row --}}
    <div class="hero-info-row fade-up delay-2">
      @if($jenis === 'kegiatan' && $konten->tanggal_mulai)
        <span><i class="bi bi-calendar-check"></i>{{ $konten->tanggal_mulai->format('d M Y') }}@if($konten->tanggal_selesai && $konten->tanggal_selesai != $konten->tanggal_mulai) – {{ $konten->tanggal_selesai->format('d M Y') }}@endif</span>
        <span class="sep">|</span>
      @else
        <span><i class="bi bi-calendar3"></i>{{ $konten->tanggal_publikasi?->format('d M Y') ?? '-' }}</span>
        <span class="sep">|</span>
      @endif
      <span><i class="bi bi-person-circle"></i>{{ $konten->user?->username ?? 'Admin' }}</span>
      @if($konten->viewer > 0)
        <span class="sep">|</span>
        <span><i class="bi bi-eye"></i>{{ number_format($konten->viewer) }} pembaca</span>
      @endif
      @if($jenis === 'kegiatan' && $konten->lokasi)
        <span class="sep">|</span>
        <span><i class="bi bi-geo-alt"></i>{{ $konten->lokasi }}</span>
      @endif
      @if($konten->pantiAsuhan)
        <span class="sep">|</span>
        <span><i class="bi bi-building-heart"></i>{{ $konten->pantiAsuhan->nama_panti }}</span>
      @endif
    </div>

  </div>
</section>

{{-- ══ KONTEN DETAIL ══ --}}
<section class="detail-layout">
  <div class="container">
    <div class="row g-4">

      {{-- ── KOLOM UTAMA ── --}}
      <div class="col-lg-8 fade-up">
        <article class="article-card">

          {{-- Gambar Cover --}}
          @if($konten->gambar)
            <img
              src="{{ asset('storage/' . $konten->gambar) }}"
              alt="{{ $konten->judul }}"
              class="article-cover"
              onerror="this.outerHTML='<div class=\'article-cover-ph\'><i class=\'bi bi-newspaper\'></i></div>'"
            />
          @else
            <div class="article-cover-ph"><i class="bi bi-newspaper"></i></div>
          @endif

          <div class="article-body">

            {{-- Author Bar --}}
            <div class="author-bar">
              <div class="author-ava"><i class="bi bi-person-fill"></i></div>
              <div class="flex-grow-1">
                <div class="author-name">{{ $konten->user?->username ?? 'Admin' }}</div>
                <div class="author-sub">
                  Dipublikasikan {{ $konten->tanggal_publikasi?->diffForHumans() ?? '-' }}
                </div>
              </div>
              <div class="d-flex gap-2 flex-wrap">
                @if($konten->viewer > 0)
                  <span class="stat-pill">
                    <i class="bi bi-eye-fill" style="color:var(--green-dark);"></i>
                    {{ number_format($konten->viewer) }}
                  </span>
                @endif
                @if($konten->kategori)
                  <span class="stat-pill" style="color:var(--orange);border-color:var(--orange);">
                    @if($konten->kategori->icon)<i class="{{ $konten->kategori->icon }}"></i>@endif
                    {{ $konten->kategori->nama_kategori }}
                  </span>
                @endif
              </div>
            </div>

            {{-- Ringkasan / Intro --}}
            @php
              $ringkasan = $konten->ringkasan ? strip_tags($konten->ringkasan) : null;
            @endphp
            @if($ringkasan)
              <div class="mb-4 ps-3" style="border-left:4px solid var(--orange);background:rgba(224,123,44,.06);padding:14px 16px;border-radius:0 10px 10px 0;font-size:.96rem;color:var(--text-dark);font-weight:500;line-height:1.7;">
                {{ $ringkasan }}
              </div>
            @endif

            {{-- ── ISI KONTEN (HTML Summernote) ── --}}
            <div class="konten-isi">
              {!! $konten->isi !!}
            </div>

            <hr class="konten-divider">

            {{-- ── INFO KEGIATAN (hanya tampil jika jenis_konten = kegiatan) ── --}}
            @if($jenis === 'kegiatan')
            <div class="mb-4" style="background:var(--green-pale);border-radius:14px;padding:20px 24px;border:1px solid rgba(0,0,0,.06);">
              <div style="font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:var(--green-dark);margin-bottom:14px;">
                <i class="bi bi-calendar-event-fill me-2"></i>Detail Kegiatan
              </div>
              <div class="row g-3">
                @if($konten->tanggal_mulai)
                <div class="col-sm-6">
                  <div style="font-size:.75rem;color:var(--text-muted);font-weight:600;">Tanggal Mulai</div>
                  <div style="font-size:.9rem;font-weight:700;color:var(--text-dark);">
                    <i class="bi bi-calendar-check me-1" style="color:var(--green-dark);"></i>
                    {{ $konten->tanggal_mulai->format('d M Y') }}
                  </div>
                </div>
                @endif
                @if($konten->tanggal_selesai)
                <div class="col-sm-6">
                  <div style="font-size:.75rem;color:var(--text-muted);font-weight:600;">Tanggal Selesai</div>
                  <div style="font-size:.9rem;font-weight:700;color:var(--text-dark);">
                    <i class="bi bi-calendar-x me-1" style="color:var(--orange);"></i>
                    {{ $konten->tanggal_selesai->format('d M Y') }}
                  </div>
                </div>
                @endif
                @if($konten->lokasi)
                <div class="col-sm-6">
                  <div style="font-size:.75rem;color:var(--text-muted);font-weight:600;">Lokasi</div>
                  <div style="font-size:.9rem;font-weight:700;color:var(--text-dark);">
                    <i class="bi bi-geo-alt-fill me-1" style="color:var(--orange);"></i>
                    {{ $konten->lokasi }}
                  </div>
                </div>
                @endif
                @if($konten->status)
                <div class="col-sm-6">
                  <div style="font-size:.75rem;color:var(--text-muted);font-weight:600;">Status</div>
                  <div style="font-size:.9rem;font-weight:700;">
                    @php
                      $stColor = match($konten->status) {
                        'berlangsung'  => '#d97706',
                        'selesai'      => 'var(--green-dark)',
                        'dibatalkan'   => '#dc3545',
                        default        => 'var(--text-muted)',
                      };
                    @endphp
                    <span style="color:{{ $stColor }};">{{ ucfirst($konten->status) }}</span>
                  </div>
                </div>
                @endif
                @if($konten->jumlah_peserta)
                <div class="col-sm-6">
                  <div style="font-size:.75rem;color:var(--text-muted);font-weight:600;">Jumlah Peserta</div>
                  <div style="font-size:.9rem;font-weight:700;color:var(--text-dark);">
                    <i class="bi bi-people-fill me-1" style="color:var(--green-dark);"></i>
                    {{ number_format($konten->jumlah_peserta) }} orang
                  </div>
                </div>
                @endif
                @if($konten->penanggung_jawab)
                <div class="col-sm-6">
                  <div style="font-size:.75rem;color:var(--text-muted);font-weight:600;">Penanggung Jawab</div>
                  <div style="font-size:.9rem;font-weight:700;color:var(--text-dark);">
                    <i class="bi bi-person-badge-fill me-1" style="color:var(--green-dark);"></i>
                    {{ $konten->penanggung_jawab }}
                  </div>
                </div>
                @endif
              </div>
            </div>
            @endif

            {{-- Info Panti (jika ada relasi) --}}
            @if($konten->pantiAsuhan)
              <div class="d-flex align-items-center gap-3 p-3 mb-4"
                   style="background:var(--green-pale);border-radius:12px;border:1px solid rgba(0,0,0,.06);">
                <div style="width:44px;height:44px;background:var(--green-dark);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="bi bi-building-heart-fill" style="color:#fff;font-size:1.1rem;"></i>
                </div>
                <div>
                  <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);">Panti Asuhan Terkait</div>
                  <div style="font-weight:700;color:var(--green-dark);font-size:.92rem;">{{ $konten->pantiAsuhan->nama_panti }}</div>
                  @if($konten->pantiAsuhan->kecamatan)
                    <div style="font-size:.78rem;color:var(--text-muted);">
                      <i class="bi bi-geo-alt me-1"></i>{{ $konten->pantiAsuhan->kecamatan }}
                    </div>
                  @endif
                </div>
                <a href="{{ route('panti.detail', $konten->pantiAsuhan->id) }}"
                   class="ms-auto btn-primary-main btn-sm" style="font-size:.8rem;padding:6px 14px;white-space:nowrap;">
                  Lihat Panti <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            @endif

            {{-- Tags --}}
            <div class="d-flex align-items-center gap-2 flex-wrap mb-4">
              <span style="font-size:.8rem;font-weight:700;color:var(--text-muted);">
                <i class="bi bi-tags me-1"></i>Tag:
              </span>
              @if($konten->kategori)
                <a href="{{ route('berita', ['kategori' => $konten->id_kategori]) }}" class="tag-pill">
                  @if($konten->kategori->icon)<i class="{{ $konten->kategori->icon }}"></i>@endif
                  {{ $konten->kategori->nama_kategori }}
                </a>
              @endif
              @if($jenis === 'kegiatan')
                <a href="{{ route('kerjasama') }}" class="tag-pill">
                  <i class="bi bi-calendar-event"></i> Kegiatan
                </a>
              @else
                <a href="{{ route('berita') }}" class="tag-pill">
                  <i class="bi bi-newspaper"></i> Berita
                </a>
              @endif
            </div>

            {{-- Share Bar --}}
            @php
              $shareUrl   = urlencode(request()->url());
              $shareTitle = urlencode($konten->judul ?? 'Berita TitikKebaikan');
            @endphp
            <div class="share-bar">
              <span class="share-label"><i class="bi bi-share-fill me-1"></i>Bagikan:</span>
              <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}"
                 target="_blank" class="share-btn share-wa">
                <i class="bi bi-whatsapp"></i> WhatsApp
              </a>
              <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                 target="_blank" class="share-btn share-fb">
                <i class="bi bi-facebook"></i> Facebook
              </a>
              <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                 target="_blank" class="share-btn share-tw">
                <i class="bi bi-twitter-x"></i> Twitter/X
              </a>
              <button class="share-btn share-copy" onclick="copyUrl()">
                <i class="bi bi-link-45deg"></i> <span id="copyText">Salin Link</span>
              </button>
            </div>

          </div>{{-- /article-body --}}
        </article>

        {{-- ── NAVIGASI ARTIKEL (Prev / Next) ── --}}
        @if(isset($prevKonten) || isset($nextKonten))
        <div class="nav-articles">
          @if(isset($prevKonten) && $prevKonten)
            <a href="{{ route('berita.detail', [$jenis, $prevKonten->slug]) }}" class="nav-art-btn prev">
              <i class="bi bi-chevron-left mt-1" style="color:var(--green-dark);flex-shrink:0;"></i>
              <div>
                <div class="nav-dir">Sebelumnya</div>
                <div class="nav-jtitle">{{ Str::limit($prevKonten->judul, 60) }}</div>
              </div>
            </a>
          @else
            <div></div>
          @endif

          @if(isset($nextKonten) && $nextKonten)
            <a href="{{ route('berita.detail', [$jenis, $nextKonten->slug]) }}" class="nav-art-btn next">
              <div>
                <div class="nav-dir">Berikutnya</div>
                <div class="nav-jtitle">{{ Str::limit($nextKonten->judul, 60) }}</div>
              </div>
              <i class="bi bi-chevron-right mt-1" style="color:var(--green-dark);flex-shrink:0;"></i>
            </a>
          @else
            <div></div>
          @endif
        </div>
        @endif

      </div>{{-- /col-lg-8 --}}

      {{-- ── SIDEBAR ── --}}
      <div class="col-lg-4 fade-up delay-2">

        {{-- Berita / Kegiatan Terkait --}}
        @if($artikelTerkait->isNotEmpty())
        <div class="sidebar-card">
          <div class="sidebar-title">
            <i class="bi bi-collection-fill me-2" style="color:var(--green-dark);"></i>
            {{ $jenis === 'kegiatan' ? 'Kegiatan Terkait' : 'Berita Terkait' }}
          </div>
          @foreach($artikelTerkait as $rel)
            <a href="{{ route('berita.detail', [$jenis, $rel->slug]) }}" class="related-item">
              @if($rel->gambar)
                <img
                  src="{{ asset('storage/' . $rel->gambar) }}"
                  alt="{{ $rel->judul }}"
                  class="related-thumb"
                  onerror="this.outerHTML='<div class=\'related-thumb-ph\'><i class=\'bi bi-journal-text\'></i></div>'"
                />
              @else
                <div class="related-thumb-ph">
                  <i class="{{ $jenis === 'kegiatan' ? 'bi-calendar-event' : 'bi-journal-text' }}"></i>
                </div>
              @endif
              <div>
                <div class="related-title">{{ $rel->judul }}</div>
                <div class="related-date">
                  <i class="bi bi-calendar3 me-1"></i>
                  @if($jenis === 'kegiatan' && $rel->tanggal_mulai)
                    {{ $rel->tanggal_mulai->format('d M Y') }}
                  @else
                    {{ $rel->tanggal_publikasi?->format('d M Y') ?? '-' }}
                  @endif
                </div>
                @if($rel->viewer > 0)
                  <div class="related-date mt-1">
                    <i class="bi bi-eye me-1"></i>{{ number_format($rel->viewer) }} pembaca
                  </div>
                @endif
              </div>
            </a>
          @endforeach
          @if($jenis === 'kegiatan')
            <a href="{{ route('kerjasama') }}" class="link-baca mt-3 d-inline-flex">
              Lihat Semua Kegiatan <i class="bi bi-arrow-right"></i>
            </a>
          @else
            <a href="{{ route('berita') }}" class="link-baca mt-3 d-inline-flex">
              Lihat Semua Berita <i class="bi bi-arrow-right"></i>
            </a>
          @endif
        </div>
        @endif

        {{-- Info Publikasi / Kegiatan --}}
        <div class="sidebar-card">
          <div class="sidebar-title">
            <i class="bi bi-info-circle-fill me-2" style="color:var(--orange);"></i>
            {{ $jenis === 'kegiatan' ? 'Info Kegiatan' : 'Info Publikasi' }}
          </div>
          <div class="d-flex flex-column gap-2" style="font-size:.84rem;">
            @if($jenis === 'kegiatan')
              @if($konten->tanggal_mulai)
              <div class="d-flex justify-content-between">
                <span style="color:var(--text-muted);">Tanggal Mulai</span>
                <span style="font-weight:600;color:var(--text-dark);">{{ $konten->tanggal_mulai->format('d M Y') }}</span>
              </div>
              @endif
              @if($konten->tanggal_selesai)
              <div class="d-flex justify-content-between">
                <span style="color:var(--text-muted);">Tanggal Selesai</span>
                <span style="font-weight:600;color:var(--text-dark);">{{ $konten->tanggal_selesai->format('d M Y') }}</span>
              </div>
              @endif
              @if($konten->lokasi)
              <div class="d-flex justify-content-between">
                <span style="color:var(--text-muted);">Lokasi</span>
                <span style="font-weight:600;color:var(--text-dark);">{{ $konten->lokasi }}</span>
              </div>
              @endif
              @if($konten->jumlah_peserta)
              <div class="d-flex justify-content-between">
                <span style="color:var(--text-muted);">Peserta</span>
                <span style="font-weight:600;color:var(--text-dark);">{{ number_format($konten->jumlah_peserta) }} orang</span>
              </div>
              @endif
              @if($konten->penanggung_jawab)
              <div class="d-flex justify-content-between">
                <span style="color:var(--text-muted);">Penanggung Jawab</span>
                <span style="font-weight:600;color:var(--text-dark);">{{ $konten->penanggung_jawab }}</span>
              </div>
              @endif
            @else
              <div class="d-flex justify-content-between">
                <span style="color:var(--text-muted);">Tanggal Terbit</span>
                <span style="font-weight:600;color:var(--text-dark);">{{ $konten->tanggal_publikasi?->format('d M Y') ?? '-' }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span style="color:var(--text-muted);">Penulis</span>
                <span style="font-weight:600;color:var(--text-dark);">{{ $konten->user?->username ?? 'Admin' }}</span>
              </div>
            @endif
            @if($konten->kategori)
            <div class="d-flex justify-content-between">
              <span style="color:var(--text-muted);">Kategori</span>
              <span style="font-weight:600;color:var(--green-dark);">{{ $konten->kategori->nama_kategori }}</span>
            </div>
            @endif
            <div class="d-flex justify-content-between">
              <span style="color:var(--text-muted);">Total Pembaca</span>
              <span style="font-weight:600;color:var(--text-dark);">{{ number_format($konten->viewer ?? 0) }}</span>
            </div>
            @if($konten->status && $jenis === 'kegiatan')
            <div class="d-flex justify-content-between">
              <span style="color:var(--text-muted);">Status</span>
              <span class="category-badge badge-cerita" style="font-size:.72rem;">{{ ucfirst($konten->status) }}</span>
            </div>
            @endif
          </div>
        </div>

        {{-- Kembali ke daftar --}}
        <div class="sidebar-card text-center" style="padding:20px;">
          <i class="bi bi-newspaper" style="font-size:2rem;color:var(--green-dark);display:block;margin-bottom:10px;"></i>
          <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:12px;">
            Jelajahi lebih banyak berita dan artikel inspiratif lainnya.
          </p>
          <a href="{{ route('berita') }}" class="btn-primary-main w-100 d-block text-center">
            <i class="bi bi-grid-fill me-1"></i> Semua Berita
          </a>
        </div>

      </div>{{-- /sidebar --}}
    </div>{{-- /row --}}
  </div>{{-- /container --}}
</section>

{{-- ══ FOOTER ══ --}}
@include('pages.landing.partials.footer')

{{-- Tombol Back to Top --}}
<button id="backTop" title="Kembali ke atas" onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <i class="bi bi-chevron-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('landing/js/shared.js') }}"></script>
<script>
  // ── Back to Top ──────────────────────────────────────────
  const btn = document.getElementById('backTop');
  window.addEventListener('scroll', () => {
    btn.classList.toggle('show', window.scrollY > 300);
  });

  // ── Salin URL ────────────────────────────────────────────
  function copyUrl() {
    navigator.clipboard.writeText(window.location.href).then(() => {
      const el = document.getElementById('copyText');
      const ori = el.textContent;
      el.textContent = 'Tersalin!';
      setTimeout(() => { el.textContent = ori; }, 2000);
    });
  }
</script>
</body>
</html>
