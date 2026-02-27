@extends('layouts.landing.app')
@section('title', 'Contact Us')

@section('content')
    <!-- ================= HERO ================= -->
    <section class="contact-hero">
        <div class="container text-center">
            <h1 class="contact-title">Contact Us</h1>
            <p class="contact-subtitle">
                Let us know your desire and plan your trip with us to experience authentic cultural experience in Sumatra.
                We will be happy to provide all the information you need throughout your travel project.
            </p>
        </div>
    </section>

    <!-- ================= CONTACT INFO ================= -->
    <section class="contact-container" id="contact-info">
        <div class="container text-center">
            <h2 class="section-title">Contact Us to Plan Your Trip</h2>
            <p class="mb-3">Mentawai Tribe</p>
            <p class="lead mb-5">
                Let us know your desire and plan your trip with us to experience authentic cultural experience in Sumatra.
                We will be happy to provide all the information you need throughout your travel project.
            </p>

            <h2 class="section-title mt-5">Let's Get Social</h2>

            <div class="social-grid mt-4">
                <div class="social-item">
                    <a href="{{ $settings->social_facebook ?? '#' }}" class="social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <p>Facebook</p>
                    <p class="small">Mentawai Tribe</p>
                </div>

                <div class="social-item">
                    <a href="https://wa.me/{{ $settings->nomor_telepon ?? '+6281261513662' }}" class="social-icon">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <p>WhatsApp</p>
                    <p class="small">+6281261513662</p>
                </div>

                <div class="social-item">
                    <a href="#" class="social-icon">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <p>Email</p>
                    <p class="small">mentawai.tribe@gmail.com</p>
                </div>

                <div class="social-item">
                    <div class="social-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <p>Address</p>
                    <p class="small">Mentawai</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= JOIN ================= -->
    <section class="join-section">
        <div class="container text-center">
            <h2 class="join-title">Join With Us</h2>
            <h3 class="join-subtitle">Mentawai Tribe</h3>
            <a href="https://wa.me/{{ $settings->nomor_telepon ?? '+6281261513662' }}" class="tribal-btn">
                <i class="fab fa-whatsapp me-2"></i> +6281261513662
            </a>
        </div>
    </section>

    <!-- ================= MAP ================= -->
    <section class="contact-container">
        <div class="container text-center">
            <h2 class="section-title">Our Location</h2>
            <div class="map-container">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.8042849999997!2d100.354397!3d-0.9613869!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4b95409e33047%3A0x49b6937ea60ef5dc!2sJl.%20Nipah%20No.2%20Berok%20Nipah%20Kec.%20Padang%20Bar.%20Kota%20Padang%2C%20Sumatera%20Barat%2025119!5e0!3m2!1sen!2sid!4v1707190000000!5m2!1sen!2sid"
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </section>
@endsection

@section('styles')
    <style>
        /* ===== Hanya CSS spesifik untuk halaman kontak ===== */
        /* Hero section dengan overlay gelap agar gambar jelas */
        .contact-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                        url('{{ asset('default-image/family.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 130px 0 80px;
            margin-top: 70px;
        }

        .contact-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .contact-subtitle {
            max-width: 800px;
            margin: 25px auto 0;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            line-height: 1.8;
            font-size: 1.2rem;
        }

        /* Container dengan latar putih/abu-abu (mengikuti main) */
        .contact-container {
            background-color: #f8f9fa;
            padding: 90px 0;
            border-top: 1px solid rgba(230, 126, 34, 0.25);
            border-bottom: 1px solid rgba(230, 126, 34, 0.25);
        }

        /* Social grid */
        .social-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 30px;
        }

        .social-item {
            background: #ffffff;
            border: 1px solid rgba(230, 126, 34, 0.2);
            padding: 35px 20px;
            border-radius: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .social-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border-color: var(--tribal-ocre);
        }

        .social-icon {
            width: 80px;
            height: 80px;
            border: 2px solid var(--tribal-ocre);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.3rem;
            margin-bottom: 20px;
            color: var(--tribal-ocre);
            background-color: rgba(230, 126, 34, 0.1);
            transition: all 0.3s;
        }

        .social-item:hover .social-icon {
            background-color: var(--tribal-ocre);
            color: #ffffff;
        }

        .social-item p {
            color: var(--tribal-light);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .social-item .small {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        /* Join section dengan overlay gelap */
        .join-section {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                        url('{{ asset('default-image/kontak.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 110px 0;
        }

        .join-title {
            font-size: 3rem;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .join-subtitle {
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            margin-bottom: 40px;
            font-size: 2rem;
        }

        /* Map container */
        .map-container {
            border-radius: 14px;
            overflow: hidden;
            border: 2px solid var(--tribal-ocre);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Responsive */
        @media (max-width: 575.98px) {
            .contact-hero {
                padding: 80px 0 50px;
            }
            .contact-title {
                font-size: 1.8rem;
            }
            .contact-subtitle {
                font-size: 0.95rem;
                padding: 0 15px;
            }
            .contact-container {
                padding: 50px 0;
            }
            .social-item {
                padding: 18px;
            }
            .social-icon {
                width: 64px;
                height: 64px;
                font-size: 1.6rem;
            }
            .join-section {
                padding: 60px 0;
            }
            .join-title {
                font-size: 2rem;
            }
            .join-subtitle {
                font-size: 1.5rem;
            }
            .map-container iframe {
                height: 260px !important;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            .contact-title {
                font-size: 2.2rem;
            }
            .contact-subtitle {
                font-size: 1rem;
            }
            .social-icon {
                width: 72px;
                height: 72px;
                font-size: 1.9rem;
            }
            .map-container iframe {
                height: 320px !important;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .contact-title {
                font-size: 2.8rem;
            }
            .social-grid {
                gap: 24px;
            }
            .map-container iframe {
                height: 380px !important;
            }
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            .contact-title {
                font-size: 3.2rem;
            }
            .map-container iframe {
                height: 420px !important;
            }
        }

        @media (min-width: 1200px) {
            .contact-title {
                font-size: 3.8rem;
            }
            .map-container iframe {
                height: 450px !important;
            }
        }
    </style>
@endsection
