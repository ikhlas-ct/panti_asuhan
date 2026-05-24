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

@include('pages.landing.partials.navbar')

{{-- HERO --}}
<section class="hero-home">
  <div class="container">
    <div class="row align-items-center gy-4">

      {{-- Kolom kiri: teks & statistik --}}
      <div class="col-lg-6 fade-up">

        {{-- Badge lokasi: tampil hanya jika alamat tersedia --}}
        @if($setting?->alamat)
          <div class="location-badge">
            <i class="bi bi-geo-alt-fill"></i> {{ $setting->alamat }}
          </div>
        @endif

        <h1>
          {{ $setting?->title_pengantar ?? '-' }}<br>

        </h1>

        <p class="lead">
          {{ $setting?->paragraf_pengantar
              ? Str::limit(strip_tags($setting->paragraf_pengantar), 200, '...')
              : 'Bersama kami, bantu anak-anak yatim piatu dan dhuafa mendapatkan kehidupan yang lebih baik.' }}
        </p>

        <div class="d-flex flex-wrap gap-3">
          <a href="{{ route('daftar-panti') }}" class="btn-primary-main">
            Jelajahi Panti Asuhan <i class="bi bi-arrow-right"></i>
          </a>
          <a href="{{ route('tentang') }}" class="btn-outline-main">Pelajari Lebih Lanjut</a>
        </div>

        {{-- Statistik utama --}}
        <div class="mt-5">
          <div class="row g-0">
            <div class="col-4 stat-item">
              <div class="stat-icon green"><i class="bi bi-house-heart"></i></div>
              <div class="stat-number">{{ $totalPanti > 0 ? $totalPanti : '-' }}</div>
              <div class="stat-label">Panti Asuhan</div>
            </div>
            <div class="col-4 stat-item">
              <div class="stat-icon orange"><i class="bi bi-people-fill"></i></div>
              <div class="stat-number">{{ $totalAnakAsuh > 0 ? $totalAnakAsuh : '-' }}</div>
              <div class="stat-label">Anak Asuh</div>
            </div>
            <div class="col-4 stat-item">
              <div class="stat-icon green"><i class="bi bi-calendar-event"></i></div>
              <div class="stat-number">{{ $totalKegiatan > 0 ? $totalKegiatan : '-' }}</div>
              <div class="stat-label">Program/Kegiatan</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Kolom kanan: hero image --}}
      <div class="col-lg-6 fade-up delay-2">
        <div class="position-relative">

          {{-- Badge jumlah anak --}}
          <div class="hero-img-badge">
            <span class="big">{{ $totalAnakAsuh > 0 ? $totalAnakAsuh . '+' : '-' }}</span>
            Anak Tersenyum
          </div>

          <div class="hero-img-card">
            @php
              // Prioritas gambar: (1) hero slide, (2) gambar_pengantar dari setting
              $heroImg        = $heroSlides->isNotEmpty() && $heroSlides->first()->image
                                  ? $heroSlides->first()->image
                                  : null;
              $heroCaption    = $heroSlides->isNotEmpty()
                                  ? ($heroSlides->first()->description ?? null)
                                  : null;
              $fallbackImg    = $setting?->gambar_pengantar ?? null;
            @endphp

            @if($heroImg)
              <img
                src="{{ asset('storage/' . $heroImg) }}"
                alt="{{ $heroSlides->first()->title ?? 'Hero Image' }}"
                onerror="this.onerror=null; this.src='{{ $fallbackImg ? asset('storage/'.$fallbackImg) : '' }}';"
              />
              <div class="hero-img-caption">{{ $heroCaption ?? '-' }}</div>

            @elseif($fallbackImg)
              <img
                src="{{ asset('storage/' . $fallbackImg) }}"
                alt="{{ $setting?->nama ?? 'Gambar Pengantar' }}"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:320px\'><i class=\'bi bi-image\'></i></div>'"
              />
              <div class="hero-img-caption">
                {{ $setting?->slogan ?? 'Melayani anak-anak dengan penuh kasih sayang' }}
              </div>

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

          {{-- Foto panti --}}
          <div class="panti-img">
            @if($panti->fotoPanti->isNotEmpty() && $panti->fotoPanti->first()->foto)
              <img
                src="{{ asset('storage/' . $panti->fotoPanti->first()->foto) }}"
                alt="{{ $panti->nama_panti ?? 'Foto Panti' }}"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:190px\'><i class=\'bi bi-image\'></i></div>'"
              />
            @else
              <div class="img-ph" style="height:190px;"><i class="bi bi-image"></i></div>
            @endif
          </div>

          <div class="panti-body">
            {{-- Nama panti --}}
            <h5 class="panti-nama fw-semibold mb-2">
              {{ $panti->nama_panti ?? '-' }}
            </h5>

            {{-- Meta: lokasi & jumlah anak --}}
            <div class="panti-meta">
              <span>
                <i class="bi bi-geo-alt"></i>
                {{ $panti->kecamatan ?? '-' }}
              </span>
              <span>
                <i class="bi bi-people"></i>
                {{ $panti->anak_asuh_count ?? 0 }} anak
              </span>
            </div>

            {{-- Keterangan / deskripsi singkat --}}
            <p class="panti-desc">
              {{ $panti->keterangan ?? 'Belum ada keterangan untuk panti ini.' }}
            </p>

            {{-- Kontak (opsional) --}}
            @if($panti->no_telp)
              <div class="panti-kontak small text-muted mb-1">
                <i class="bi bi-telephone"></i> {{ $panti->no_telp }}
              </div>
            @endif
            @if($panti->nama_kontak)
              <div class="panti-kontak small text-muted mb-2">
                <i class="bi bi-person"></i> {{ $panti->nama_kontak }}
              </div>
            @endif

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
      <a href="{{ route('daftar-panti') }}" class="link-all">
        Lihat Semua Panti Asuhan <i class="bi bi-arrow-right"></i>
      </a>
    </div>

  </div>
</section>
@else
{{-- Fallback jika belum ada panti aktif --}}
<section class="py-5" style="background:var(--white);">
  <div class="container py-4 text-center">
    <h2 class="section-title">Cari Panti Asuhan</h2>
    <p class="section-sub text-muted">Belum ada data panti asuhan yang tersedia saat ini.</p>
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
      <a href="{{ route('berita') }}" class="link-all">
        Lihat Semua Berita <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    <div class="row g-4">
      @foreach($beritaTerbaru as $berita)
      <div class="col-sm-6 col-lg-4 fade-up delay-{{ min($loop->iteration, 3) }}">
        <div class="berita-card">

          {{-- Gambar berita --}}
          <div class="berita-img" style="height:180px;">
            @if($berita->gambar)
              <img
                src="{{ asset('storage/' . $berita->gambar) }}"
                alt="{{ $berita->judul ?? 'Gambar Berita' }}"
                style="height:180px;"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:180px\'><i class=\'bi bi-journal-text\'></i></div>'"
              />
            @else
              <div class="img-ph" style="height:180px;"><i class="bi bi-journal-text"></i></div>
            @endif
          </div>

          <div class="berita-body">
            <div class="berita-meta">

              {{-- Author & waktu --}}
              <div class="author-row">
                <div class="author-avatar"><i class="bi bi-person-fill"></i></div>
                <div>
                  <div class="author-name">
                    {{ $berita->user?->username ?? 'Admin' }}
                  </div>
                  <div class="author-time">
                    {{ $berita->tanggal_publikasi?->diffForHumans() ?? '-' }}
                  </div>
                </div>
              </div>

              {{-- Badge kategori --}}
              @if($berita->kategori?->nama_kategori)
                <span class="category-badge badge-cerita">
                  {{ $berita->kategori->nama_kategori }}
                </span>
              @endif

            </div>

            {{-- Judul berita --}}
            <div class="berita-title">{{ $berita->judul ?? '-' }}</div>

            {{-- Ringkasan: prioritas field ringkasan, fallback strip dari isi --}}
            @php
              $ringkasanTampil = $berita->ringkasan
                ? strip_tags($berita->ringkasan)
                : Str::limit(strip_tags($berita->isi ?? ''), 120, '...');
            @endphp
            @if($ringkasanTampil)
              <p class="berita-ringkasan small text-muted mt-1 mb-2">
                {{ Str::limit($ringkasanTampil, 120, '...') }}
              </p>
            @endif

            {{-- Link baca --}}
            @if($berita->slug)
              <a href="{{ route('berita.detail', $berita->slug) }}" class="link-baca">
                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
              </a>
            @else
              <span class="link-baca text-muted">Tautan tidak tersedia</span>
            @endif

          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</section>
@else
{{-- Fallback jika belum ada berita --}}
<section class="py-5" style="background:var(--cream);">
  <div class="container py-4 text-center">
    <h2 class="section-title">Berita & Kegiatan</h2>
    <p class="section-sub text-muted">Belum ada berita yang tersedia saat ini.</p>
  </div>
</section>
@endif

{{-- FOOTER --}}
@include('pages.landing.partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('landing/js/shared.js') }}"></script>
</body>
</html>
