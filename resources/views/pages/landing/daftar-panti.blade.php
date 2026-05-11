<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $setting?->nama ?? 'TitikKebaikan' }} – Daftar Panti Asuhan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
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

{{-- PAGE HEADER --}}
<section class="page-header">
  <h1 class="fade-up">Daftar Panti Asuhan</h1>
  <p class="sub fade-up delay-1">Temukan dan bantu panti asuhan untuk memberikan masa depan<br>yang lebih cerah bagi anak-anak.</p>
</section>

{{-- TAGLINE --}}
<div class="tagline-row">
  <div class="container">
    <p class="fade-up">
      Jelajahi sebaran lokasi panti asuhan kami dan <span class="orange">dampak nyata</span>
      yang telah kami berikan kepada <span class="green">anak-anak</span> di berbagai wilayah
    </p>
  </div>
</div>

<section class="py-3">
  <div class="container">

    {{-- MAP SECTION --}}
    <div class="map-section fade-up">
      <div class="row g-3">
        <div class="col-lg-8">
          <div class="map-section-title"><i class="bi bi-map-fill"></i> Peta Interaktif</div>
          <div class="map-iframe-wrap">
            <div id="leaflet-map" style="width:100%;height:320px;border-radius:10px;"></div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="map-sidebar-label">Lokasi Kecamatan</div>
          <select class="map-select mb-3" id="kecamatanFilter" onchange="filterByKecamatan(this.value)">
            <option value="semua">Semua Lokasi</option>
            @foreach($kecamatanList as $kec)
              <option value="{{ strtolower($kec) }}">{{ $kec }}</option>
            @endforeach
          </select>
          <div class="map-stat">
            <div class="map-stat-icon"><i class="bi bi-people-fill"></i></div>
            <div><div class="map-stat-num">{{ $totalAnakAsuh }}</div><div class="map-stat-lbl">Total Anak</div></div>
          </div>
          <div class="map-stat">
            <div class="map-stat-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <div><div class="map-stat-num">{{ $totalPanti }}</div><div class="map-stat-lbl">Lokasi Aktif</div></div>
          </div>
          <div class="map-stat">
            <div class="map-stat-icon"><i class="bi bi-calendar-check-fill"></i></div>
            <div><div class="map-stat-num">{{ $pantiList->count() }}</div><div class="map-stat-lbl">Panti Terdaftar</div></div>
          </div>
        </div>
      </div>
    </div>

    {{-- STATS ROW --}}
    <div class="stats-row fade-up">
      <div class="row g-3">
        <div class="col-4">
          <div class="stat-box sb-green">
            <div class="stat-box-icon"><i class="bi bi-house-heart-fill"></i></div>
            <div><div class="stat-box-num">{{ $totalPanti }}</div><div class="stat-box-lbl">Panti Asuhan</div></div>
          </div>
        </div>
        <div class="col-4">
          <div class="stat-box sb-dark">
            <div class="stat-box-icon"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-box-num">{{ $totalAnakAsuh }}</div><div class="stat-box-lbl">Anak Asuh</div></div>
          </div>
        </div>
        <div class="col-4">
          <div class="stat-box sb-orange">
            <div class="stat-box-icon"><i class="bi bi-star-fill"></i></div>
            <div><div class="stat-box-num">{{ $kecamatanList->count() }}</div><div class="stat-box-lbl">Kecamatan</div></div>
          </div>
        </div>
      </div>
    </div>

    {{-- SEARCH + FILTER --}}
    <form method="GET" action="{{ route('daftar-panti') }}" class="fade-up mb-4">
      <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
        <div class="search-box flex-grow-1" style="max-width:400px;">
          <i class="bi bi-search"></i>
          <input type="text" name="q" placeholder="Cari nama panti atau alamat..." value="{{ request('q') }}"/>
        </div>
      </div>
      <div class="filter-tabs" id="kecTabs">
        <button type="submit" name="kecamatan" value="semua"
          class="filter-tab {{ !request('kecamatan') || request('kecamatan') === 'semua' ? 'active active-orange' : '' }}">Semua</button>
        @foreach($kecamatanList as $kec)
          <button type="submit" name="kecamatan" value="{{ $kec }}"
            class="filter-tab {{ request('kecamatan') === $kec ? 'active active-orange' : '' }}">{{ $kec }}</button>
        @endforeach
      </div>
    </form>

    {{-- PANTI CARDS --}}
    <div class="row g-0" id="pantiGrid">
      @forelse($pantiList as $panti)
      <div class="col-md-6 {{ $loop->odd ? 'pe-md-3' : 'ps-md-3' }} panti-item"
           data-kec="{{ strtolower($panti->kecamatan) }}"
           data-name="{{ strtolower($panti->nama_panti) }}">
        <div class="daftar-card">
          <div class="daftar-card-header">
            <div>
              <div class="daftar-card-name">{{ $panti->nama_panti }}</div>
              <div class="daftar-card-addr">
                <i class="bi bi-geo-alt-fill" style="color:var(--orange);"></i> {{ $panti->alamat }}
              </div>
            </div>
            <button class="btn-heart" style="flex-shrink:0;"><i class="bi bi-heart"></i></button>
          </div>
          <div class="daftar-img">
            @if($panti->fotoPanti->isNotEmpty())
              <img src="{{ asset('storage/'.$panti->fotoPanti->first()->foto) }}" alt="{{ $panti->nama_panti }}"
                onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:180px\'><i class=\'bi bi-image\'></i></div>'"/>
            @else
              <div class="img-ph" style="height:180px;"><i class="bi bi-image"></i></div>
            @endif
          </div>
          <div class="daftar-stats">
            <div class="daftar-stat">
              <i class="bi bi-people-fill"></i>
              <span class="daftar-stat-val">{{ $panti->anak_asuh_count }}</span>
              <span class="daftar-stat-lbl">Anak Asuh</span>
            </div>
            <div class="daftar-stat">
              <i class="bi bi-geo-alt-fill"></i>
              <span class="daftar-stat-val">{{ $panti->kecamatan }}</span>
              <span class="daftar-stat-lbl">Kecamatan</span>
            </div>
          </div>
          <div class="daftar-contact">
            @if($panti->no_telp)
              <span><i class="bi bi-telephone-fill"></i> {{ $panti->no_telp }}</span>
            @endif
            @if($panti->email)
              <span><i class="bi bi-envelope-fill"></i> {{ $panti->email }}</span>
            @endif
          </div>
          <div class="daftar-actions">
            <a href="{{ route('kerjasama') }}" class="btn-donasi"><i class="bi bi-heart-fill"></i> Donasi</a>
            <a href="{{ route('panti.detail', $panti->id) }}" class="btn-kunjungi">Kunjungi</a>
            <button class="btn-heart" style="width:38px;height:38px;border-radius:var(--radius-sm);flex-shrink:0;">
              <i class="bi bi-share"></i>
            </button>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center py-5">
        <i class="bi bi-house" style="font-size:3rem;color:var(--text-muted);"></i>
        <p class="mt-3" style="color:var(--text-muted);">Belum ada panti asuhan terdaftar.</p>
      </div>
      @endforelse
    </div>

    {{-- CTA --}}
    <div class="panti-cta fade-up">
      <h2>Mari Berbagi <span>Kebahagiaan</span> untuk Panti Asuhan</h2>
      <p>Setiap bantuan Anda, sekecil apapun, dapat memberikan harapan dan masa depan yang lebih cerah untuk anak-anak di panti asuhan ini.</p>
      <div class="d-flex justify-content-center flex-wrap gap-3">
        <a href="{{ route('kerjasama') }}" class="btn-orange-main">Mulai Berdonasi</a>
        <a href="{{ route('tentang') }}" class="btn-outline-white">Pelajari Lebih Lanjut</a>
      </div>
    </div>

  </div>
</section>

{{-- FOOTER --}}
@include('pages.landing.partials.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('landing/js/shared.js') }}"></script>
<script>
  // ── Leaflet Map ──────────────────────────────────────────
  const map = L.map('leaflet-map').setView([-7.9797, 112.6304], 12);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  const greenIcon = L.divIcon({
    html: '<div style="background:#1c3a2e;width:26px;height:26px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3);"></div>',
    iconSize: [26,26], iconAnchor: [13,26], popupAnchor: [0,-28], className: ''
  });

  // Data panti dari controller (JSON)
  const pantiData = @json($pantiMapData);
  pantiData.forEach(p => {
    L.marker([p.lat, p.lng], { icon: greenIcon }).addTo(map)
      .bindPopup(`<b style="color:#1c3a2e">${p.nama}</b><br><small>${p.alamat}</small>`);
  });

  // ── Filter kecamatan (dropdown peta) ────────────────────
  function filterByKecamatan(kec) {
    document.querySelectorAll('.panti-item').forEach(item => {
      item.style.display = (kec === 'semua' || item.dataset.kec === kec) ? '' : 'none';
    });
  }
</script>
</body>
</html>
