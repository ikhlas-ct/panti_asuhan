{{-- FOOTER PUBLIC --}}
{{-- $settings di-inject global oleh View::composer di AppServiceProvider --}}
<footer>
    <div class="container">
        <div class="row g-5">

            {{-- Kolom 1: Brand & Sosmed --}}
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <div class="brand-icon"><i class="bi bi-heart-fill"></i></div>
                    {{ $settings->nama ?? 'TitikKebaikan' }}
                </div>
                <div class="footer-brand-sub">{{ $settings->alamat }}</div>
                <div class="footer-tagline">
                    <i class="bi bi-heart-fill"></i> {{ $settings->slogan }}
                </div>
                <div class="d-flex gap-2">
                    @if ($settings->social_instagram)
                        <a href="{{ $settings->social_instagram }}" class="social-btn" target="_blank" rel="noopener">
                            <i class="bi bi-instagram"></i>
                        </a>
                    @endif
                    @if ($settings->social_facebook)
                        <a href="{{ $settings->social_facebook }}" class="social-btn" target="_blank" rel="noopener">
                            <i class="bi bi-facebook"></i>
                        </a>
                    @endif
                    @if ($settings->social_twitter)
                        <a href="{{ $settings->social_twitter }}" class="social-btn" target="_blank" rel="noopener">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                    @endif
                    @if ($settings->social_youtube)
                        <a href="{{ $settings->social_youtube }}" class="social-btn" target="_blank" rel="noopener">
                            <i class="bi bi-youtube"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Kolom 2: Kontak --}}
            <div class="col-lg-4 col-md-6">
                <div class="footer-heading">Kontak Kami</div>

                @if ($settings->alamat)
                    <div class="footer-contact-item">
                        <div class="contact-icon ci-orange"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <div class="contact-label">Alamat</div>
                            <div class="contact-value">{{ $settings->alamat }}</div>
                        </div>
                    </div>
                @endif

                @if ($settings->email)
                    <div class="footer-contact-item">
                        <div class="contact-icon ci-mail"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <div class="contact-label">Email</div>
                            <div class="contact-value">{{ $settings->email }}</div>
                        </div>
                    </div>
                @endif

                @if ($settings->nomor_telepon)
                    <div class="footer-contact-item">
                        <div class="contact-icon ci-phone"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <div class="contact-label">Telepon</div>
                            <div class="contact-value">{{ $settings->nomor_telepon }}</div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Kolom 3: Menu Cepat --}}
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
        © {{ date('Y') }} {{ $settings->nama ?? 'TitikKebaikan' }}. All rights reserved.
    </div>
</footer>
