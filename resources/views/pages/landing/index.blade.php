<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $setting?->nama ?? 'TitikKebaikan' }} – Beranda</title>
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
<section class="hero-home">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-6 fade-up">
        <div class="location-badge"><i class="bi bi-geo-alt-fill"></i> {{ $setting?->alamat }}</div>
        <h1>{{ $setting?->title_pengantar ?? 'Salurkan' }}<br>Kebaikan<br><span>Kepada Mereka</span></h1>
        <p class="lead">{{ $setting?->paragraf_pengantar }}</p>
        <div class="d-flex flex-wrap gap-3">
          <a href="{{ route('daftar-panti') }}" class="btn-primary-main">Jelajahi Panti Asuhan <i class="bi bi-arrow-right"></i></a>
          <a href="{{ route('tentang') }}" class="btn-outline-main">Pelajari Lebih Lanjut</a>
        </div>
        <div class="mt-5">
          <div class="row g-0">
            <div class="col-4 stat-item">
              <div class="stat-icon green"><i class="bi bi-house-heart"></i></div>
              <div class="stat-number">{{ $totalPanti }}</div>
              <div class="stat-label">Panti Asuhan</div>
            </div>
            <div class="col-4 stat-item">
              <div class="stat-icon orange"><i class="bi bi-people-fill"></i></div>
              <div class="stat-number">{{ $totalAnakAsuh }}</div>
              <div class="stat-label">Anak Asuh</div>
            </div>
            <div class="col-4 stat-item">
              <div class="stat-icon green"><i class="bi bi-calendar-event"></i></div>
              <div class="stat-number">{{ $totalKegiatan }}</div>
              <div class="stat-label">Program/Kegiatan</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 fade-up delay-2">
        <div class="position-relative">
          <div class="hero-img-badge"><span class="big">{{ $totalAnakAsuh }}+</span>Anak Tersenyum</div>
          <div class="hero-img-card">
            @if($heroSlides->isNotEmpty())
              <img src="{{ asset('storage/'.$heroSlides->first()->image) }}" alt="{{ $heroSlides->first()->title }}"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:320px\'><i class=\'bi bi-image\'></i></div>'"/>
              <div class="hero-img-caption">{{ $heroSlides->first()->description }}</div>
            @else
              <div class="img-ph" style="height:320px;"><i class="bi bi-image"></i></div>
              <div class="hero-img-caption">Melayani anak-anak dengan penuh kasih sayang</div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- CARI PANTI --}}
@if($pantiFeatured->isNotEmpty())
<section class="py-5" style="background:var(--white);">
  <div class="container py-4">
    <div class="text-center">
      <h2 class="section-title">Cari Panti Asuhan</h2>
      <p class="section-sub">Temukan panti asuhan terpercaya yang membutuhkan dukungan Anda</p>
    </div>
    <div class="row g-4">
      @foreach($pantiFeatured as $panti)
      <div class="col-md-4 fade-up delay-{{ $loop->iteration }}">
        <div class="panti-card">
          <div class="panti-img">
            @if($panti->fotoPanti->isNotEmpty())
              <img src="{{ asset('storage/'.$panti->fotoPanti->first()->foto) }}" alt="{{ $panti->nama_panti }}"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:190px\'><i class=\'bi bi-image\'></i></div>'"/>
            @else
              <div class="img-ph" style="height:190px;"><i class="bi bi-image"></i></div>
            @endif
          </div>
          <div class="panti-body">
            <div class="panti-meta">
              <span><i class="bi bi-geo-alt"></i>{{ $panti->kecamatan }}</span>
              <span><i class="bi bi-people"></i>{{ $panti->anak_asuh_count }} anak</span>
            </div>
            <p class="panti-desc">{{ $panti->keterangan }}</p>
            <div class="kebutuhan-label">Kebutuhan Mendesak:</div>
          </div>
          <div class="panti-footer">
            <a href="{{ route('panti.detail', $panti->id) }}" class="btn-detail">Lihat Detail</a>
            <button class="btn-heart"><i class="bi bi-heart"></i></button>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div class="text-center mt-5">
      <a href="{{ route('daftar-panti') }}" class="link-all">Lihat Semua Panti Asuhan <i class="bi bi-arrow-right"></i></a>
    </div>
  </div>
</section>
@endif

{{-- BERITA TERBARU --}}
@if($beritaTerbaru->isNotEmpty())
<section class="py-5" style="background:var(--cream);">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
      <div>
        <h2 class="section-title mb-1">Berita & Kegiatan</h2>
        <p class="section-sub mb-0">Update terkini seputar dunia panti asuhan</p>
      </div>
      <a href="{{ route('berita') }}" class="link-all">Lihat Semua Berita <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-4">
      @foreach($beritaTerbaru as $berita)
      <div class="col-md-4 fade-up delay-{{ $loop->iteration }}">
        <div class="berita-card">
          <div class="berita-img" style="height:180px;">
            @if($berita->gambar)
              <img src="{{ asset('storage/'.$berita->gambar) }}" alt="{{ $berita->judul }}" style="height:180px;"
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
                  <div class="author-name">{{ $berita->user?->username ?? 'Admin' }}</div>
                  <div class="author-time">{{ $berita->tanggal_publikasi?->diffForHumans() }}</div>
                </div>
              </div>
              @if($berita->kategori)
                <span class="category-badge badge-cerita">{{ $berita->kategori->nama_kategori }}</span>
              @endif
            </div>
            <div class="berita-title">{{ $berita->judul }}</div>
            <a href="{{ route('berita.detail', $berita->slug) }}" class="link-baca">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- FOOTER --}}
@include('pages.landing.partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('landing/js/shared.js') }}"></script>
</body>
</html>
