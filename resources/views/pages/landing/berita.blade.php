<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $setting?->nama ?? 'TitikKebaikan' }} – Berita & Artikel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet"/>
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
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active':'' }}" href="{{ route('home') }}">Beranda</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('berita*') ? 'active':'' }}" href="{{ route('berita') }}">Berita</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('daftar-panti*') ? 'active':'' }}" href="{{ route('daftar-panti') }}">Daftar Panti</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('kerjasama*') ? 'active':'' }}" href="{{ route('kerjasama') }}">Kerjasama Kami</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('tentang') ? 'active':'' }}" href="{{ route('tentang') }}">Tentang Kami</a></li>
      </ul>
      <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
        <button class="btn-bookmark"><i class="bi bi-bookmark"></i> Bookmark</button>
        <a href="#" class="btn-admin">Admin Dashboard</a>
      </div>
    </div>
  </div>
</nav>

{{-- HERO --}}
<section class="hero-berita">
  <div class="deco-circle" style="width:200px;height:200px;top:-60px;left:-60px;"></div>
  <div class="deco-circle" style="width:120px;height:120px;bottom:20px;right:80px;background:rgba(224,123,44,.05);"></div>
  <div class="container">
    <div class="section-badge fade-up">Berita &amp; Artikel</div>
    <h1 class="fade-up delay-1">
      Temukan <span>Berita Terkini</span> dan <span>Inspirasi</span><br>
      untuk <span class="span2">Perubahan</span> dari Komunitas Panti Asuhan
    </h1>
    <p class="lead fade-up delay-2">Jelajahi kisah dampak sosial, data visual, dan panduan peduli dari dunia panti asuhan.</p>
    <div class="d-flex justify-content-center flex-wrap gap-3 fade-up delay-3">
      <a href="#semua" class="btn-primary-main"><i class="bi bi-newspaper"></i> Artikel Terbaru</a>
      <a href="#semua" class="btn-orange-main"><i class="bi bi-stars"></i> Inspirasi Harian</a>
    </div>
  </div>
</section>

{{-- FEATURED --}}
@if($beritaFeatured)
<section class="py-5" style="background:var(--white);">
  <div class="container py-4">
    <div class="text-center mb-5 fade-up">
      <div style="width:46px;height:46px;background:var(--green-pale);border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.1rem;color:var(--green-dark);">
        <i class="bi bi-bookmark-star-fill"></i>
      </div>
      <h2 class="section-title">Berita dan Artikel <span>Terkini</span></h2>
    </div>
    <div class="row g-4 align-items-stretch">

      {{-- BIG CARD --}}
      <div class="col-lg-7 fade-up delay-1">
        <div class="featured-big h-100">
          <div class="feat-img">
            @if($beritaFeatured->gambar)
              <img src="{{ asset('storage/'.$beritaFeatured->gambar) }}" alt="{{ $beritaFeatured->judul }}"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:260px\'><i class=\'bi bi-image\'></i></div>'"/>
            @else
              <div class="img-ph" style="height:260px;"><i class="bi bi-image"></i></div>
            @endif
          </div>
          <div class="feat-body">
            <div class="feat-date">
              <i class="bi bi-calendar3 me-1"></i>{{ $beritaFeatured->tanggal_publikasi?->format('d M, Y') }}
              &nbsp;·&nbsp; <span style="color:var(--text-muted);">{{ $beritaFeatured->user?->username ?? 'Admin' }}</span>
            </div>
            <div class="feat-title">{{ $beritaFeatured->judul }}</div>
            <p class="feat-excerpt">{{ $beritaFeatured->ringkasan }}</p>
            <a href="{{ route('berita.detail', $beritaFeatured->slug) }}" class="link-baca">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>

      {{-- SMALL CARDS --}}
      <div class="col-lg-5 fade-up delay-2 d-flex flex-column gap-3">
        @foreach($beritaPopuler as $pop)
        <div class="berita-sm h-auto">
          <div class="berita-sm-img">
            @if($pop->gambar)
              <img src="{{ asset('storage/'.$pop->gambar) }}" alt="{{ $pop->judul }}"/>
            @else
              <div class="img-ph" style="height:80px;"><i class="bi bi-journal-text"></i></div>
            @endif
          </div>
          <div class="berita-sm-body">
            @if($pop->kategori)
              <span class="category-badge badge-cerita mb-1 d-inline-block">{{ $pop->kategori->nama_kategori }}</span>
            @endif
            <div class="berita-sm-title">{{ $pop->judul }}</div>
            <div class="d-flex align-items-center gap-2 mt-2">
              <div class="author-avatar" style="width:22px;height:22px;"><i class="bi bi-person-fill" style="font-size:.7rem;"></i></div>
              <span style="font-size:.73rem;color:var(--text-muted);">
                {{ $pop->tanggal_publikasi?->format('d M, Y') }} · {{ $pop->user?->username ?? 'Admin' }}
              </span>
            </div>
            <a href="{{ route('berita.detail', $pop->slug) }}" class="link-baca mt-2 d-inline-flex">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
        @endforeach
      </div>

    </div>
  </div>
</section>
@endif

{{-- SEMUA BERITA --}}
<section id="semua" class="py-5" style="background:var(--cream);">
  <div class="container py-4">
    <div class="text-center mb-5 fade-up">
      <div style="width:50px;height:50px;background:var(--orange);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.2rem;color:var(--white);">
        <i class="bi bi-grid-3x3-gap-fill"></i>
      </div>
      <h2 class="section-title">Semua Berita dan <span>Artikel</span></h2>
      <p class="section-sub">Temukan beragam cerita inspiratif dan informasi terkini dari dunia panti asuhan</p>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <form method="GET" action="{{ route('berita') }}">
      <div style="background:var(--white);border-radius:var(--radius);padding:18px 20px;box-shadow:var(--shadow);margin-bottom:24px;" class="fade-up">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
          <div class="d-flex align-items-center gap-2" style="font-size:.85rem;font-weight:600;color:var(--text-dark);">
            <i class="bi bi-grid-1x2-fill" style="color:var(--green-dark);"></i> Browse by Categories
          </div>
          <div class="search-box" style="min-width:220px;max-width:280px;">
            <i class="bi bi-search"></i>
            <input type="text" name="q" placeholder="Cari Berita/Artikel" value="{{ request('q') }}"/>
          </div>
        </div>
      </div>

      {{-- FILTER TABS KATEGORI --}}
      <div class="filter-tabs mb-4 fade-up delay-1">
        <button type="submit" name="jenis" value="semua"
          class="filter-tab {{ !request('jenis') || request('jenis') === 'semua' ? 'active active-orange' : '' }}">
          <i class="bi bi-grid-fill me-1"></i> Semua Artikel
        </button>
        <button type="submit" name="jenis" value="artikel"
          class="filter-tab {{ request('jenis') === 'artikel' ? 'active' : '' }}">
          <i class="bi bi-heart me-1"></i> Cerita Inspiratif
        </button>
        <button type="submit" name="jenis" value="berita"
          class="filter-tab {{ request('jenis') === 'berita' ? 'active' : '' }}">
          <i class="bi bi-newspaper me-1"></i> Berita
        </button>
        @foreach($kategoris as $kat)
        <button type="submit" name="kategori" value="{{ $kat->id_kategori }}"
          class="filter-tab {{ request('kategori') == $kat->id_kategori ? 'active' : '' }}">
          @if($kat->icon)<i class="{{ $kat->icon }} me-1"></i>@endif {{ $kat->nama_kategori }}
        </button>
        @endforeach
      </div>
    </form>

    {{-- ARTIKEL GRID --}}
    <div class="row g-4">
      @forelse($beritaList as $item)
      <div class="col-md-4 fade-up delay-{{ ($loop->index % 3) + 1 }}">
        <div class="berita-card">
          <div class="berita-img" style="height:180px;">
            @if($item->gambar)
              <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->judul }}" style="height:180px;"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:180px\'><i class=\'bi bi-journal-text\'></i></div>'"/>
            @else
              <div class="img-ph" style="height:180px;"><i class="bi bi-journal-text"></i></div>
            @endif
          </div>
          <div class="berita-body">
            <div class="berita-meta">
              <div class="author-row">
                <div class="author-avatar"><i class="bi bi-person-fill"></i></div>
                <div>
                  <div class="author-name">{{ $item->user?->username ?? 'Admin' }}</div>
                  <div class="author-time">{{ $item->tanggal_publikasi?->diffForHumans() }}</div>
                </div>
              </div>
              @if($item->kategori)
                <span class="category-badge badge-cerita">{{ $item->kategori->nama_kategori }}</span>
              @endif
            </div>
            <div class="berita-title">{{ $item->judul }}</div>
            <p class="berita-excerpt">{{ $item->ringkasan }}</p>
            <div class="d-flex justify-content-between align-items-center">
              <a href="{{ route('berita.detail', $item->slug) }}" class="link-baca">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
              <button class="btn-heart" style="width:32px;height:32px;border-radius:6px;"><i class="bi bi-bookmark"></i></button>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center py-5">
        <i class="bi bi-inbox" style="font-size:3rem;color:var(--text-muted);"></i>
        <p class="mt-3" style="color:var(--text-muted);">Belum ada artikel tersedia.</p>
      </div>
      @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($beritaList->hasPages())
    <div class="pagination-custom fade-up mt-4">
      <a class="page-btn {{ $beritaList->onFirstPage() ? 'disabled' : '' }}" href="{{ $beritaList->previousPageUrl() }}">
        <i class="bi bi-chevron-left"></i>
      </a>
      @foreach($beritaList->getUrlRange(1, $beritaList->lastPage()) as $page => $url)
        <a class="page-btn {{ $page == $beritaList->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
      @endforeach
      <a class="page-btn {{ !$beritaList->hasMorePages() ? 'disabled' : '' }}" href="{{ $beritaList->nextPageUrl() }}">
        <i class="bi bi-chevron-right"></i>
      </a>
    </div>
    @endif

  </div>
</section>

{{-- FOOTER --}}
@include('pages.landing.partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('landing/js/shared.js') }}"></script>
</body>
</html>
