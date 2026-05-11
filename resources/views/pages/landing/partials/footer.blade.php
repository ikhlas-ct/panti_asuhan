<footer>
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4 col-md-6">
        <div class="footer-brand">
          <div class="brand-icon"><i class="bi bi-heart-fill"></i></div>
          {{ $setting?->nama ?? 'TitikKebaikan' }}
        </div>
        <div class="footer-brand-sub">{{ $setting?->alamat }}</div>
        <p class="footer-desc">{{ $setting?->about_us }}</p>
        <div class="footer-tagline"><i class="bi bi-heart-fill"></i> {{ $setting?->slogan }}</div>
        <div class="d-flex gap-2">
          @if($setting?->social_instagram)<a href="{{ $setting->social_instagram }}" class="social-btn"><i class="bi bi-instagram"></i></a>@endif
          @if($setting?->social_facebook)<a href="{{ $setting->social_facebook }}" class="social-btn"><i class="bi bi-facebook"></i></a>@endif
          @if($setting?->social_twitter)<a href="{{ $setting->social_twitter }}" class="social-btn"><i class="bi bi-twitter-x"></i></a>@endif
          @if($setting?->social_youtube)<a href="{{ $setting->social_youtube }}" class="social-btn"><i class="bi bi-youtube"></i></a>@endif
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="footer-heading">Kontak Kami</div>
        @if($setting?->alamat)
        <div class="footer-contact-item">
          <div class="contact-icon ci-orange"><i class="bi bi-geo-alt-fill"></i></div>
          <div><div class="contact-label">Alamat</div><div class="contact-value">{{ $setting->alamat }}</div></div>
        </div>
        @endif
        @if($setting?->email)
        <div class="footer-contact-item">
          <div class="contact-icon ci-mail"><i class="bi bi-envelope-fill"></i></div>
          <div><div class="contact-label">Email</div><div class="contact-value">{{ $setting->email }}</div></div>
        </div>
        @endif
        @if($setting?->nomor_telepon)
        <div class="footer-contact-item">
          <div class="contact-icon ci-phone"><i class="bi bi-telephone-fill"></i></div>
          <div><div class="contact-label">Telepon</div><div class="contact-value">{{ $setting->nomor_telepon }}</div></div>
        </div>
        @endif
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="footer-heading">Menu Cepat</div>
        <div class="footer-menu">
          <a href="{{ route('home') }}">Beranda</a>
          <a href="{{ route('daftar-panti') }}">Daftar Panti</a>
          <a href="{{ route('berita') }}">Berita</a>
          <a href="{{ route('kerjasama') }}">Kerjasama</a>
          <a href="{{ route('tentang') }}">Tentang Kami</a>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    © {{ date('Y') }} {{ $setting?->nama ?? 'TitikKebaikan' }}. All rights reserved.
  </div>
</footer>
