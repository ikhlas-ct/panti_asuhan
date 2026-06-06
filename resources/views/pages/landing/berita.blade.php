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

@include('pages.landing.partials.navbar')

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

          {{-- Gambar featured --}}
          <div class="feat-img">
            @if($beritaFeatured->gambar)
              <img
                src="{{ asset('storage/' . $beritaFeatured->gambar) }}"
                alt="{{ $beritaFeatured->judul ?? 'Berita Terkini' }}"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:260px\'><i class=\'bi bi-image\'></i></div>'"
              />
            @else
              <div class="img-ph" style="height:260px;"><i class="bi bi-image"></i></div>
            @endif
          </div>

          <div class="feat-body">
            {{-- Tanggal & author --}}
            <div class="feat-date">
              <i class="bi bi-calendar3 me-1"></i>
              {{ $beritaFeatured->tanggal_publikasi?->format('d M, Y') ?? '-' }}
              &nbsp;·&nbsp;
              <span style="color:var(--text-muted);">
                {{ $beritaFeatured->user?->username ?? 'Admin' }}
              </span>
              @if($beritaFeatured->kategori?->nama_kategori)
                &nbsp;·&nbsp;
                <span class="category-badge badge-cerita">
                  {{ $beritaFeatured->kategori->nama_kategori }}
                </span>
              @endif
            </div>

            {{-- Judul --}}
            <div class="feat-title">{{ $beritaFeatured->judul ?? '-' }}</div>

            {{-- Ringkasan: strip HTML Summernote, fallback ke isi --}}
            @php
              $featRingkasan = $beritaFeatured->ringkasan
                ? strip_tags($beritaFeatured->ringkasan)
                : Str::limit(strip_tags($beritaFeatured->isi ?? ''), 180, '...');
            @endphp
            @if($featRingkasan)
              <p class="feat-excerpt">{{ Str::limit($featRingkasan, 180, '...') }}</p>
            @endif

            {{-- Link baca --}}
            @if($beritaFeatured->slug)
              <a href="{{ route('berita.detail', ['berita', $beritaFeatured->slug]) }}" class="link-baca">
                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
              </a>
            @else
              <span class="link-baca text-muted">Tautan tidak tersedia</span>
            @endif
          </div>
        </div>
      </div>

      {{-- SMALL CARDS (2 berita populer) --}}
      <div class="col-lg-5 fade-up delay-2 d-flex flex-column gap-3">
        @forelse($beritaPopuler as $pop)
        <div class="berita-sm h-auto">

          {{-- Gambar kecil --}}
          <div class="berita-sm-img">
            @if($pop->gambar)
              <img
                src="{{ asset('storage/' . $pop->gambar) }}"
                alt="{{ $pop->judul ?? 'Berita' }}"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:80px\'><i class=\'bi bi-journal-text\'></i></div>'"
              />
            @else
              <div class="img-ph" style="height:80px;"><i class="bi bi-journal-text"></i></div>
            @endif
          </div>

          <div class="berita-sm-body">
            {{-- Badge kategori --}}
            @if($pop->kategori?->nama_kategori)
              <span class="category-badge badge-cerita mb-1 d-inline-block">
                {{ $pop->kategori->nama_kategori }}
              </span>
            @endif

            {{-- Judul --}}
            <div class="berita-sm-title">{{ $pop->judul ?? '-' }}</div>

            {{-- Meta: tanggal & author --}}
            <div class="d-flex align-items-center gap-2 mt-2">
              <div class="author-avatar" style="width:22px;height:22px;">
                <i class="bi bi-person-fill" style="font-size:.7rem;"></i>
              </div>
              <span style="font-size:.73rem;color:var(--text-muted);">
                {{ $pop->tanggal_publikasi?->format('d M, Y') ?? '-' }}
                · {{ $pop->user?->username ?? 'Admin' }}
              </span>
            </div>

            {{-- Viewer --}}
            @if($pop->viewer > 0)
              <div class="mt-1" style="font-size:.72rem;color:var(--text-muted);">
                <i class="bi bi-eye me-1"></i>{{ number_format($pop->viewer) }} pembaca
              </div>
            @endif

            {{-- Link baca --}}
            @if($pop->slug)
              <a href="{{ route('berita.detail', ['berita', $pop->slug]) }}" class="link-baca mt-2 d-inline-flex">
                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
              </a>
            @else
              <span class="link-baca mt-2 d-inline-flex text-muted">Tautan tidak tersedia</span>
            @endif
          </div>
        </div>
        @empty
        {{-- Tidak ada berita populer selain featured --}}
        @endforelse
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

      {{--
        FILTER TABS KATEGORI
        Catatan: jenis_konten di DB enum('kegiatan','berita') — tidak ada 'artikel'.
        Tab kategori dinamis dari tabel kategori.
      --}}
      <div class="filter-tabs mb-4 fade-up delay-1">
        <button type="submit" name="kategori" value=""
          class="filter-tab {{ !request('kategori') ? 'active active-orange' : '' }}">
          <i class="bi bi-grid-fill me-1"></i> Semua
        </button>
        @forelse($kategoris as $kat)
        <button type="submit" name="kategori" value="{{ $kat->id_kategori }}"
          class="filter-tab {{ request('kategori') == $kat->id_kategori ? 'active' : '' }}">
          @if($kat->icon)
            <i class="{{ $kat->icon }} me-1"></i>
          @endif
          {{ $kat->nama_kategori ?? '-' }}
        </button>
        @empty
        {{-- Tidak ada kategori, hanya tampil tab Semua --}}
        @endforelse
      </div>

      {{-- Tombol search tersembunyi agar form bisa submit via input --}}
      <button type="submit" class="d-none">Cari</button>
    </form>

    {{-- ARTIKEL GRID --}}
    <div class="row g-4">
      @forelse($beritaList as $item)
      <div class="col-sm-6 col-lg-4 fade-up delay-{{ ($loop->index % 3) + 1 }}">
        <div class="berita-card h-100 d-flex flex-column">

          {{-- Gambar --}}
          <div class="berita-img" style="height:180px;">
            @if($item->gambar)
              <img
                src="{{ asset('storage/' . $item->gambar) }}"
                alt="{{ $item->judul ?? 'Berita' }}"
                style="height:180px;"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:180px\'><i class=\'bi bi-journal-text\'></i></div>'"
              />
            @else
              <div class="img-ph" style="height:180px;"><i class="bi bi-journal-text"></i></div>
            @endif
          </div>

          <div class="berita-body flex-grow-1 d-flex flex-column">
            {{-- Meta: author & waktu --}}
            <div class="berita-meta">
              <div class="author-row">
                <div class="author-avatar"><i class="bi bi-person-fill"></i></div>
                <div>
                  <div class="author-name">{{ $item->user?->username ?? 'Admin' }}</div>
                  <div class="author-time">
                    {{ $item->tanggal_publikasi?->diffForHumans() ?? '-' }}
                  </div>
                </div>
              </div>
              @if($item->kategori?->nama_kategori)
                <span class="category-badge badge-cerita">
                  {{ $item->kategori->nama_kategori }}
                </span>
              @endif
            </div>

            {{-- Judul --}}
            <div class="berita-title">{{ $item->judul ?? '-' }}</div>

            {{-- Ringkasan: strip HTML Summernote, fallback ke isi --}}
            @php
              $ringkasan = $item->ringkasan
                ? strip_tags($item->ringkasan)
                : Str::limit(strip_tags($item->isi ?? ''), 120, '...');
            @endphp
            @if($ringkasan)
              <p class="berita-excerpt">{{ Str::limit($ringkasan, 120, '...') }}</p>
            @endif

            {{-- Footer: link baca & bookmark --}}
            <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
              @if($item->slug)
                <a href="{{ route('berita.detail', ['berita', $item->slug]) }}" class="link-baca">
                  Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                </a>
              @else
                <span class="link-baca text-muted">Tautan tidak tersedia</span>
              @endif
              <button class="btn-heart" style="width:32px;height:32px;border-radius:6px;" title="Simpan">
                <i class="bi bi-bookmark"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center py-5">
        <i class="bi bi-inbox" style="font-size:3rem;color:var(--text-muted);"></i>
        <p class="mt-3" style="color:var(--text-muted);">
          @if(request('q'))
            Tidak ada berita yang cocok dengan pencarian "<strong>{{ request('q') }}</strong>".
          @elseif(request('kategori'))
            Belum ada berita di kategori ini.
          @else
            Belum ada berita tersedia.
          @endif
        </p>
        @if(request()->hasAny(['q', 'kategori']))
          <a href="{{ route('berita') }}" class="btn-outline-main mt-2">Lihat Semua Berita</a>
        @endif
      </div>
      @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($beritaList->hasPages())
    <div class="pagination-custom fade-up mt-4">
      {{-- Tombol prev --}}
      @if($beritaList->onFirstPage())
        <span class="page-btn disabled"><i class="bi bi-chevron-left"></i></span>
      @else
        <a class="page-btn" href="{{ $beritaList->previousPageUrl() }}">
          <i class="bi bi-chevron-left"></i>
        </a>
      @endif

      {{-- Nomor halaman --}}
      @foreach($beritaList->getUrlRange(1, $beritaList->lastPage()) as $page => $url)
        <a class="page-btn {{ $page == $beritaList->currentPage() ? 'active' : '' }}" href="{{ $url }}">
          {{ $page }}
        </a>
      @endforeach

      {{-- Tombol next --}}
      @if($beritaList->hasMorePages())
        <a class="page-btn" href="{{ $beritaList->nextPageUrl() }}">
          <i class="bi bi-chevron-right"></i>
        </a>
      @else
        <span class="page-btn disabled"><i class="bi bi-chevron-right"></i></span>
      @endif
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
