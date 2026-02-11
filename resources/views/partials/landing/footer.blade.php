    <!-- Footer with Bootstrap Grid -->
    <footer id="contact" class="py-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-lg-0 mb-4">
                    <div class="footer-column">
                        <h3>About Mentawai Tribal Experience</h3>
                        <p>We are a specialized tour operator focused on authentic cultural experiences of the Mentawai
                            tribe.
                            Our commitment is to sustainable tourism that respects local culture.</p>
                        <div class="social-links">
                            <a href="{{ $settings->social_instagram ?? '#' }}" aria-label="Instagram"><i
                                    class="fab fa-instagram"></i></a>
                            <a href="{{ $settings->social_facebook ?? '#' }}" aria-label="Facebook"><i
                                    class="fab fa-facebook-f"></i></a>
                            <a href="{{ $settings->social_twitter ?? '#' }}" aria-label="Twitter"><i
                                    class="fab fa-twitter"></i></a>
                            <a href="{{ $settings->social_youtube ?? '#' }}" aria-label="YouTube"><i
                                    class="fab fa-youtube"></i></a>

                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-column">
                        <h3>Menu</h3>
                        <ul class="footer-links">
                            <li><a href="{{ route('landing.index') }}">Home</a></li>
                            <li><a href="{{ route('landing.blog', ['jenis' => 'artikel']) }}">Articles</a></li>
                            <li><a href="{{ route('landing.blog', ['jenis' => 'aktivitas']) }}">Activities</a></li>
                            <li><a href="{{ route('landing.ethical') }}">Ethical Tourism</a></li>
                            <li><a href="{{ route('landing.transportasi') }}">Transportation</a></li>
                            <li><a href="{{ route('landing.contact') }}">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer-column">
                        <h3>Contact & Reservations</h3>
                        <ul class="footer-links">
                            <li><i class="fas fa-map-marker-alt me-2"></i>
                                {{ $settings->alamat ?? 'Padang, West Sumatra, Indonesia' }}</li>
                            <li><i class="fas fa-phone me-2"></i> {{ $settings->nomor_telepon ?? '+62 812 3456 7890' }}
                            </li>
                            <li><i class="fas fa-envelope me-2"></i>
                                {{ $settings->email ?? 'info@mentawaitribal.com' }}
                            </li>
                            <li><i class="fas fa-clock me-2"></i> Open: Monday - Saturday, 08:00 - 17:00 WIB</li>
                        </ul>
                        <a href="https://wa.me/{{ $settings->nomor_telepon ?? '+6281261513662' }}"
                            class="tribal-btn mt-3" style="display: inline-block;">
                            <i class="fab fa-whatsapp me-2"></i> WhatsApp Consultation
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="copyright">
                        <p>&copy; 2026 Mentawai Ethical Tours. All rights reserved. | All tours follow the <a
                                href="#" class="text-warning">Mentawai Tourism Code of Ethics</a> and are approved
                            by
                            the Mentawai Customary Council.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
