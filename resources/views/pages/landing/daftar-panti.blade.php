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

@include('pages.landing.partials.navbar')

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
        @if(request('q') || (request('kecamatan') && request('kecamatan') !== 'semua'))
          <a href="{{ route('daftar-panti') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Reset
          </a>
        @endif
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
      <div class="col-md-6 {{ $loop->odd ? 'pe-md-3' : 'ps-md-3' }} panti-item mb-4"
           data-kec="{{ strtolower($panti->kecamatan) }}"
           data-name="{{ strtolower($panti->nama_panti) }}">
        <div class="daftar-card">
          <div class="daftar-card-header">
            <div>
              <div class="daftar-card-name">{{ $panti->nama_panti }}</div>
              <div class="daftar-card-addr">
                <i class="bi bi-geo-alt-fill" style="color:var(--orange);"></i>
                {{ $panti->alamat }}{{ $panti->kecamatan ? ', Kec. '.$panti->kecamatan : '' }}
              </div>
            </div>
            {{-- Badge status --}}
            <span class="badge bg-success" style="flex-shrink:0;font-size:.7rem;">Aktif</span>
          </div>

          {{-- FOTO --}}
          <div class="daftar-img">
            @if($panti->fotoPanti->isNotEmpty())
              <img src="{{ asset('storage/'.$panti->fotoPanti->first()->foto) }}"
                   alt="{{ $panti->nama_panti }}"
                   style="width:100%;height:180px;object-fit:cover;"
                   onerror="this.parentElement.innerHTML='<div class=\'img-ph\' style=\'height:180px\'><i class=\'bi bi-house-fill\'></i></div>'"/>
            @else
              <div class="img-ph" style="height:180px;display:flex;align-items:center;justify-content:center;background:#f3f4f6;border-radius:8px;">
                <i class="bi bi-house-fill" style="font-size:2.5rem;color:#ccc;"></i>
              </div>
            @endif
          </div>

          {{-- STATS --}}
          <div class="daftar-stats">
            <div class="daftar-stat">
              <i class="bi bi-people-fill"></i>
              <span class="daftar-stat-val">{{ $panti->anak_asuh_count }}</span>
              <span class="daftar-stat-lbl">Anak Asuh</span>
            </div>
            <div class="daftar-stat">
              <i class="bi bi-geo-alt-fill"></i>
              <span class="daftar-stat-val">{{ $panti->kecamatan ?? '-' }}</span>
              <span class="daftar-stat-lbl">Kecamatan</span>
            </div>
            @if($panti->kelurahan)
            <div class="daftar-stat">
              <i class="bi bi-map-fill"></i>
              <span class="daftar-stat-val">{{ $panti->kelurahan }}</span>
              <span class="daftar-stat-lbl">Kelurahan</span>
            </div>
            @endif
          </div>

          {{-- KONTAK --}}
          <div class="daftar-contact">
            @if($panti->no_telp)
              <span><i class="bi bi-telephone-fill"></i> {{ $panti->no_telp }}</span>
            @endif
            @if($panti->email)
              <span><i class="bi bi-envelope-fill"></i> {{ $panti->email }}</span>
            @endif
            @if($panti->nama_kontak)
              <span><i class="bi bi-person-fill"></i> {{ $panti->nama_kontak }}</span>
            @endif
          </div>

          {{-- KETERANGAN (preview singkat) --}}
          @if($panti->keterangan)
          <div class="px-3 pb-2" style="font-size:.82rem;color:var(--text-muted);line-height:1.5;">
            {{ Str::limit($panti->keterangan, 100) }}
          </div>
          @endif

          {{-- ACTIONS --}}
          <div class="daftar-actions">
            {{-- Tombol Donasi: cek login --}}
            @auth
              @if(Auth::user()->role === 'donatur' && Auth::user()->donatur)
                {{-- Sudah login sebagai donatur → langsung ke form donasi --}}
                <a href="{{ route('donasi.create', ['panti_asuhan_id' => $panti->id]) }}"
                   class="btn-donasi">
                  <i class="bi bi-heart-fill"></i> Donasi
                </a>
              @elseif(in_array(Auth::user()->role, ['admin_dinsos','admin_panti']))
                {{-- Admin → ke halaman donasi index --}}
                <a href="{{ route('donasi.index') }}" class="btn-donasi">
                  <i class="bi bi-heart-fill"></i> Donasi
                </a>
              @else
                {{-- Login tapi belum punya profil donatur --}}
                <button class="btn-donasi" onclick="showLoginModal('Akun Anda belum terdaftar sebagai donatur.')">
                  <i class="bi bi-heart-fill"></i> Donasi
                </button>
              @endif
            @else
              {{-- Belum login → tampil modal login --}}
              <button class="btn-donasi"
                      data-panti-id="{{ $panti->id }}"
                      data-panti-nama="{{ $panti->nama_panti }}"
                      onclick="showLoginModal(this)">
                <i class="bi bi-heart-fill"></i> Donasi
              </button>
            @endauth

            <a href="{{ route('panti.detail', $panti->id) }}" class="btn-kunjungi">
              <i class="bi bi-eye"></i> Detail
            </a>

            {{-- Share --}}
            <button class="btn-heart" style="width:38px;height:38px;border-radius:var(--radius-sm);flex-shrink:0;"
                    onclick="shareKartu('{{ $panti->nama_panti }}', '{{ route('panti.detail', $panti->id) }}')"
                    title="Bagikan">
              <i class="bi bi-share"></i>
            </button>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center py-5">
        <i class="bi bi-house" style="font-size:3rem;color:var(--text-muted);"></i>
        <p class="mt-3" style="color:var(--text-muted);">
          @if(request('q') || request('kecamatan'))
            Panti asuhan tidak ditemukan untuk pencarian ini.
            <a href="{{ route('daftar-panti') }}" class="d-block mt-2">Lihat semua panti</a>
          @else
            Belum ada panti asuhan terdaftar.
          @endif
        </p>
      </div>
      @endforelse
    </div>

    {{-- CTA --}}
    <div class="panti-cta fade-up">
      <h2>Mari Berbagi <span>Kebahagiaan</span> untuk Panti Asuhan</h2>
      <p>Setiap bantuan Anda, sekecil apapun, dapat memberikan harapan dan masa depan yang lebih cerah untuk anak-anak di panti asuhan ini.</p>
      <div class="d-flex justify-content-center flex-wrap gap-3">
        @auth
          @if(Auth::user()->role === 'donatur' && Auth::user()->donatur)
            <a href="{{ route('donasi.create') }}" class="btn-orange-main">Mulai Berdonasi</a>
          @else
            <a href="{{ route('donasi.index') }}" class="btn-orange-main">Lihat Donasi</a>
          @endif
        @else
          <button onclick="showLoginModal(null)" class="btn-orange-main">Mulai Berdonasi</button>
        @endauth
        <a href="{{ route('tentang') }}" class="btn-outline-white">Pelajari Lebih Lanjut</a>
      </div>
    </div>

  </div>
</section>

{{-- MODAL: Perlu Login untuk Donasi --}}
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">
      <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#1c3a2e,#2d5a44);color:#fff;padding:1.5rem 1.5rem 1rem;">
        <div>
          <div style="width:44px;height:44px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:.75rem;">
            <i class="bi bi-heart-fill" style="font-size:1.2rem;"></i>
          </div>
          <h5 class="modal-title mb-0 fw-700" id="loginModalLabel">Masuk untuk Berdonasi</h5>
          <p class="mb-0 mt-1" style="font-size:.85rem;opacity:.8;" id="loginModalSubtitle">
            Silakan masuk terlebih dahulu untuk melanjutkan donasi Anda.
          </p>
        </div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div id="pantiDonasiInfo" class="mb-3 p-3 rounded-3" style="background:#f8fffe;border:1px solid #e0f0ea;display:none;">
          <div style="font-size:.8rem;color:#666;">Donasi untuk:</div>
          <div id="pantiDonasiNama" style="font-weight:600;color:#1c3a2e;"></div>
        </div>
        <p style="font-size:.9rem;color:#555;line-height:1.6;">
          Untuk melakukan donasi, Anda perlu masuk ke akun donatur Anda. Jika belum memiliki akun, Anda dapat mendaftar terlebih dahulu.
        </p>
        <div class="d-grid gap-2 mt-3">
          <a id="loginBtn" href="{{ route('login') }}" class="btn btn-lg fw-600"
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

  const pantiData = @json($pantiMapData);
  pantiData.forEach(p => {
    const marker = L.marker([p.lat, p.lng], { icon: greenIcon }).addTo(map);
    marker.bindPopup(
      `<b style="color:#1c3a2e">${p.nama}</b><br>
       <small>${p.alamat}</small><br>
       <a href="/daftar-panti/${p.id}" style="color:#1c3a2e;font-size:.8rem;font-weight:600;">
         Lihat Detail →
       </a>`
    );
  });

  // ── Filter kecamatan (dropdown peta) ────────────────────
  function filterByKecamatan(kec) {
    document.querySelectorAll('.panti-item').forEach(item => {
      item.style.display = (kec === 'semua' || item.dataset.kec === kec) ? '' : 'none';
    });
  }

  // ── Modal Login untuk Donasi ─────────────────────────────
  function showLoginModal(btnOrMessage) {
    const modal = new bootstrap.Modal(document.getElementById('loginModal'));
    const pantiInfo = document.getElementById('pantiDonasiInfo');
    const pantiNama = document.getElementById('pantiDonasiNama');
    const subtitle  = document.getElementById('loginModalSubtitle');
    const loginBtn  = document.getElementById('loginBtn');

    if (btnOrMessage && typeof btnOrMessage === 'object') {
      // Dipanggil dari tombol kartu panti
      const pantiId   = btnOrMessage.dataset.pantiId;
      const namaPanti = btnOrMessage.dataset.pantiNama;

      if (namaPanti) {
        pantiInfo.style.display = '';
        pantiNama.textContent   = namaPanti;
      }
      subtitle.textContent = 'Silakan masuk terlebih dahulu untuk berdonasi ke panti ini.';
      // Setelah login, redirect ke form donasi dengan panti yang dipilih
      loginBtn.href = `{{ route('login') }}?redirect={{ urlencode(route('donasi.create')) }}&panti_asuhan_id=${pantiId}`;
    } else if (typeof btnOrMessage === 'string') {
      // Pesan custom (misal: belum punya profil donatur)
      pantiInfo.style.display = 'none';
      subtitle.textContent = btnOrMessage;
      loginBtn.href = '{{ route('login') }}';
    } else {
      // Dipanggil dari CTA bawah
      pantiInfo.style.display = 'none';
      subtitle.textContent = 'Silakan masuk terlebih dahulu untuk melanjutkan donasi Anda.';
      loginBtn.href = '{{ route('login') }}';
    }
    modal.show();
  }

  // ── Share ─────────────────────────────────────────────────
  function shareKartu(nama, url) {
    if (navigator.share) {
      navigator.share({ title: nama, text: `Yuk bantu ${nama}!`, url: url });
    } else {
      navigator.clipboard.writeText(url).then(() => {
        alert('Link berhasil disalin!');
      });
    }
  }
</script>
</body>
</html>
