<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $setting?->nama ?? 'TitikKebaikan' }} – Tentang Kami</title>
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
<section class="hero-tentang">
  <div class="deco-circle" style="width:300px;height:300px;top:-80px;right:-60px;"></div>
  <div class="deco-circle" style="width:160px;height:160px;bottom:20px;right:220px;background:rgba(224,123,44,.06);"></div>
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-6 fade-up">
        <div class="section-badge">Platform Kebaikan</div>
        <h1>
          {{ $setting?->nama ?? 'Titik' }}<br>
          <span style="display:inline-flex;align-items:center;gap:10px;">
            <span style="background:var(--green-dark);color:var(--white);width:36px;height:36px;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;font-size:.9rem;">
              <i class="bi bi-heart-fill"></i>
            </span>
            Kebaikan
          </span><br>
          <span class="line-orange">
            <span style="background:var(--orange);color:var(--white);width:30px;height:30px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:.8rem;vertical-align:middle;margin-right:8px;">
              <i class="bi bi-stars"></i>
            </span>
            {{ $setting?->slogan ?? 'Wujudkan Harapan' }}
          </span>
        </h1>
        <p class="lead mt-3">{{ $setting?->paragraf_pengantar }}</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <button class="tentang-tag-btn ttag-green">
            <i class="bi bi-house-heart"></i> {{ $stats['total_panti'] }}+ Panti Asuhan
          </button>
          <button class="tentang-tag-btn ttag-orange">
            <i class="bi bi-geo-alt-fill"></i> {{ $setting?->alamat }}
          </button>
        </div>
      </div>
      <div class="col-lg-6 fade-up delay-2">
        <div class="position-relative">
          <div style="border-radius:20px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.13);">
            @if($setting?->gambar_pengantar)
              <img src="{{ asset('storage/'.$setting->gambar_pengantar) }}" alt="Tentang Kami"
                style="width:100%;height:340px;object-fit:cover;display:block;"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:340px\'><i class=\'bi bi-image\'></i></div>'"/>
            @else
              <div class="img-ph" style="height:340px;"><i class="bi bi-image"></i></div>
            @endif
          </div>
          <div style="position:absolute;bottom:16px;right:16px;background:var(--orange);color:var(--white);width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.1rem;box-shadow:0 4px 14px rgba(224,123,44,.4);">
            <i class="bi bi-heart-fill"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- TENTANG -- MISI VISI NILAI --}}
<section class="py-5" style="background:var(--white);">
  <div class="container py-4">
    <div class="text-center mb-5 fade-up">
      <div style="width:50px;height:50px;background:var(--green-dark);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:1.2rem;color:var(--white);">
        <i class="bi bi-heart-fill"></i>
      </div>
      <h2 class="section-title">Tentang <span>{{ $setting?->nama ?? 'TitikKebaikan' }}</span></h2>
      <p class="section-sub" style="max-width:580px;margin:0 auto;">{{ $setting?->about_us }}</p>
    </div>

    {{-- Misi Visi Nilai — dari why_choose_us atau statis --}}
    @if($setting?->why_choose_us)
      <div class="row g-4">
        <div class="col-12 fade-up">
          <div style="background:var(--cream);border-radius:var(--radius);padding:24px;" class="text-center">
            {!! $setting->why_choose_us !!}
          </div>
        </div>
      </div>
    @else
      <div class="row g-4">
        <div class="col-md-4 fade-up delay-1">
          <div class="mvn-card">
            <div class="mvn-bar green"></div>
            <div class="mvn-body">
              <div class="mvn-icon green"><i class="bi bi-bullseye"></i></div>
              <div class="mvn-title">Misi Kami</div>
              <p class="mvn-text">Membangun jembatan kebaikan antara masyarakat dan panti asuhan untuk menciptakan ekosistem berbagi yang berkelanjutan dan berdampak nyata.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 fade-up delay-2">
          <div class="mvn-card">
            <div class="mvn-bar orange"></div>
            <div class="mvn-body">
              <div class="mvn-icon orange"><i class="bi bi-eye-fill"></i></div>
              <div class="mvn-title" style="color:var(--orange);">Visi Kami</div>
              <p class="mvn-text">Menjadi platform terdepan yang menghubungkan kebaikan hati masyarakat dengan kebutuhan anak-anak panti asuhan secara transparan dan profesional.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 fade-up delay-3">
          <div class="mvn-card">
            <div class="mvn-bar green"></div>
            <div class="mvn-body">
              <div class="mvn-icon green"><i class="bi bi-gem"></i></div>
              <div class="mvn-title">Nilai Kami</div>
              <p class="mvn-text">Transparansi, empati, integritas, dan keberlanjutan dalam setiap langkah kebaikan yang kami wujudkan bersama untuk masa depan yang lebih baik.</p>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
</section>

{{-- STATISTIK --}}
<section class="py-5" style="background:var(--cream);">
  <div class="container py-4">
    <div class="text-center mb-5 fade-up">
      <div class="section-badge">Dampak Nyata</div>
      <h2 class="section-title mb-3">Ayo Berbuat <span>Kebaikan!</span></h2>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-6 col-md-3 fade-up delay-1">
        <div class="stat-box sb-green" style="border-radius:var(--radius);flex-direction:column;text-align:center;padding:28px 16px;">
          <div class="stat-box-icon" style="margin:0 auto 12px;"><i class="bi bi-house-heart-fill"></i></div>
          <div class="stat-box-num">{{ $stats['total_panti'] }}</div>
          <div class="stat-box-lbl">Panti Asuhan</div>
        </div>
      </div>
      <div class="col-6 col-md-3 fade-up delay-2">
        <div class="stat-box sb-dark" style="border-radius:var(--radius);flex-direction:column;text-align:center;padding:28px 16px;">
          <div class="stat-box-icon" style="margin:0 auto 12px;"><i class="bi bi-people-fill"></i></div>
          <div class="stat-box-num">{{ $stats['total_anak'] }}</div>
          <div class="stat-box-lbl">Anak Asuh</div>
        </div>
      </div>
      <div class="col-6 col-md-3 fade-up delay-3">
        <div class="stat-box sb-orange" style="border-radius:var(--radius);flex-direction:column;text-align:center;padding:28px 16px;">
          <div class="stat-box-icon" style="margin:0 auto 12px;"><i class="bi bi-calendar-event-fill"></i></div>
          <div class="stat-box-num">{{ $stats['total_kegiatan'] }}</div>
          <div class="stat-box-lbl">Program/Kegiatan</div>
        </div>
      </div>
      <div class="col-6 col-md-3 fade-up delay-4">
        <div class="stat-box sb-green" style="border-radius:var(--radius);flex-direction:column;text-align:center;padding:28px 16px;">
          <div class="stat-box-icon" style="margin:0 auto 12px;"><i class="bi bi-person-badge-fill"></i></div>
          <div class="stat-box-num">{{ $stats['total_pengurus'] }}</div>
          <div class="stat-box-lbl">Pengurus Aktif</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- TIM PEGAWAI --}}
@if($timPegawai->isNotEmpty())
<section class="py-5" style="background:var(--white);">
  <div class="container py-4">
    <div class="text-center mb-5 fade-up">
      <h2 class="section-title">Tim <span>Kami</span></h2>
      <p class="section-sub">Orang-orang berdedikasi di balik platform ini</p>
    </div>
    <div class="row g-4 justify-content-center">
      @foreach($timPegawai as $pegawai)
      <div class="col-6 col-md-3 fade-up delay-{{ ($loop->index % 4) + 1 }}">
        <div style="background:var(--white);border:1px solid #e8eee9;border-radius:var(--radius);padding:24px 16px;text-align:center;box-shadow:var(--shadow);transition:transform .25s;">
          <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;margin:0 auto 14px;background:var(--cream-dark);">
            @if($pegawai->foto_profil)
              <img src="{{ asset('storage/'.$pegawai->foto_profil) }}" alt="{{ $pegawai->nama }}"
                style="width:100%;height:100%;object-fit:cover;"
                onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;\'><i class=\'bi bi-person-fill\' style=\'font-size:2rem;color:var(--text-muted);\'></i></div>'"/>
            @else
              <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-fill" style="font-size:2rem;color:var(--text-muted);"></i>
              </div>
            @endif
          </div>
          <div style="font-size:.95rem;font-weight:700;color:var(--text-dark);margin-bottom:4px;">{{ $pegawai->nama }}</div>
          <div style="font-size:.78rem;color:var(--orange);font-weight:600;margin-bottom:10px;">{{ $pegawai->posisi }}</div>
          @if($pegawai->deskripsi)
            <p style="font-size:.78rem;color:var(--text-muted);line-height:1.6;margin-bottom:12px;">{{ Str::limit($pegawai->deskripsi, 80) }}</p>
          @endif
          <div class="d-flex justify-content-center gap-2">
            @if($pegawai->instagram)<a href="{{ $pegawai->instagram }}" class="social-btn" style="width:30px;height:30px;font-size:.85rem;background:var(--green-pale);color:var(--green-dark);"><i class="bi bi-instagram"></i></a>@endif
            @if($pegawai->facebook)<a href="{{ $pegawai->facebook }}" class="social-btn" style="width:30px;height:30px;font-size:.85rem;background:var(--green-pale);color:var(--green-dark);"><i class="bi bi-facebook"></i></a>@endif
            @if($pegawai->twitter)<a href="{{ $pegawai->twitter }}" class="social-btn" style="width:30px;height:30px;font-size:.85rem;background:var(--green-pale);color:var(--green-dark);"><i class="bi bi-twitter-x"></i></a>@endif
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
