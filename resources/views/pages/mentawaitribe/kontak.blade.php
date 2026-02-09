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
            <a href="https://wa.me/{{ $settings->nomor_telepon ?? '+6281261513662' }}" class="wa-btn">
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
        /* ================= ROOT COLOR ================= */
        :root {
            --brown-dark: #120d06;
            --brown-main: #1b140a;
            --brown-card: #241a0e;
            --brown-light: #3a2a18;
            --white: #f5f1e8;
            --white-soft: #c8bfae;
            --gold: #b89b5e;
        }

        /* ================= BASE ================= */
        body {
            background: linear-gradient(180deg, #0f0b05, #1b140a, #241a0e);
            color: var(--white);
            font-family: 'Trebuchet MS', Arial, sans-serif;
        }

        /* ================= HERO ================= */
        .contact-hero {
            background:
                linear-gradient(rgba(18, 13, 6, 0.9), rgba(18, 13, 6, 0.85)),
                url('{{ asset("default-image/family.jpg") }}');
            background-size: cover;
            background-position: center;
            padding: 130px 0 80px;
        }

        .contact-title {
            font-size: 3.5rem;
            font-weight: 700;
        }

        .contact-subtitle {
            max-width: 800px;
            margin: 25px auto 0;
            color: var(--white-soft);
            line-height: 1.8;
        }

        /* ================= SECTION ================= */
        .contact-container {
            background: linear-gradient(180deg, var(--brown-main), var(--brown-card));
            padding: 90px 0;
            border-top: 1px solid rgba(184, 155, 94, .25);
            border-bottom: 1px solid rgba(184, 155, 94, .25);
        }

        .section-title {
            font-size: 2.6rem;
            margin-bottom: 30px;
            position: relative;
        }

        .section-title::after {
            content: '';
            width: 60px;
            height: 3px;
            background: var(--gold);
            display: block;
            margin: 15px auto 0;
        }

        /* ================= SOCIAL ================= */
        .social-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 30px;
        }

        .social-item {
            background: linear-gradient(145deg, #2a1f13, #1f160c);
            border: 1px solid rgba(184, 155, 94, .25);
            padding: 35px 20px;
            border-radius: 16px;
            transition: .3s ease;
        }

        .social-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, .7);
        }

        .social-icon {
            width: 80px;
            height: 80px;
            border: 2px solid var(--gold);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.3rem;
            margin-bottom: 20px;
            color: var(--white);
        }

        /* ================= JOIN ================= */
        .join-section {
            background:
                linear-gradient(rgba(18, 13, 6, .95), rgba(18, 13, 6, .95)),
                url('https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            padding: 110px 0;
        }

        .join-title {
            font-size: 3rem;
        }

        .join-subtitle {
            color: var(--white-soft);
            margin-bottom: 40px;
        }

        .wa-btn {
            background: var(--gold);
            color: #1a120b;
            padding: 15px 45px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
        }

        .wa-btn:hover {
            background: #d6b66f;
        }

        /* ================= MAP ================= */
        .map-container {
            border-radius: 14px;
            overflow: hidden;
            border: 2px solid rgba(184, 155, 94, .3);
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 575.98px) {
            .contact-hero {
                padding: 80px 0 50px;
            }

            .contact-title {
                font-size: 1.8rem;
            }

            .contact-subtitle {
                max-width: 100%;
                margin: 15px auto 0;
                font-size: .95rem;
                padding: 0 15px;
            }

            .contact-container {
                padding: 50px 0;
            }

            .section-title {
                font-size: 1.6rem;
            }

            .social-item {
                padding: 18px;
            }

            .social-icon {
                width: 64px;
                height: 64px;
                font-size: 1.6rem;
            }

            .wa-btn {
                padding: 12px 28px;
                font-size: 1rem;
            }

            .map-container iframe {
                height: 260px !important;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            .contact-hero {
                padding: 100px 0 60px;
            }

            .contact-title {
                font-size: 2.2rem;
            }

            .contact-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .social-item {
                padding: 22px;
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
            .contact-hero {
                padding: 120px 0 70px;
            }

            .contact-title {
                font-size: 2.8rem;
            }

            .section-title {
                font-size: 2.2rem;
            }

            .social-grid {
                gap: 24px;
            }

            .map-container iframe {
                height: 380px !important;
            }
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            .contact-hero {
                padding: 130px 0 80px;
            }

            .contact-title {
                font-size: 3.2rem;
            }

            .section-title {
                font-size: 2.4rem;
            }

            .map-container iframe {
                height: 420px !important;
            }
        }

        @media (min-width: 1200px) {
            .contact-hero {
                padding: 140px 0 90px;
            }

            .contact-title {
                font-size: 3.8rem;
            }

            .section-title {
                font-size: 2.6rem;
            }

            .map-container iframe {
                height: 450px !important;
            }
        }
    </style>
@endsection
