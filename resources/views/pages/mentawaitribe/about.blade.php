@extends('layouts.landing.app')

@section('title', 'About Us - Mentawai Tribe')

@section('styles')
    <style>
        .story-content {
            background-color: rgba(40, 32, 20, 0.5);
            padding: 2.5rem;
            border-radius: 16px;
            border: 1px solid rgba(184, 134, 11, 0.2);
            backdrop-filter: blur(2px);
            margin-bottom: 4rem;
        }

        .story-content p {
            margin-bottom: 1.8rem;
            font-size: 1.05rem;
            color: #f0e6d0;
        }

        .culture-features {
            list-style: none;
            margin: 2.5rem 0;
            padding-left: 0;
        }

        .culture-features li {
            margin-bottom: 1.2rem;
            padding-left: 2.2rem;
            position: relative;
            font-size: 1.02rem;
        }

        .culture-features li::before {
            content: "▸";
            position: absolute;
            left: 0;
            color: var(--tribal-ocre);
            font-size: 1.4rem;
            line-height: 1;
        }

        .culture-features strong {
            color: var(--tribal-ocre);
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .member-card {
            background: linear-gradient(145deg, rgba(47, 79, 47, 0.2), rgba(28, 20, 8, 0.95));
            border-radius: 20px;
            padding: 1.8rem 1.5rem;
            border: 1px solid rgba(184, 134, 11, 0.25);
            transition: all 0.4s;
            display: flex;
            flex-direction: column;
            height: 100%;
            backdrop-filter: blur(2px);
        }

        .member-card:hover {
            transform: translateY(-8px);
            border-color: var(--tribal-ocre);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.7);
        }

        .member-img-container {
            width: 140px;
            height: 140px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid var(--tribal-ocre);
            background: var(--tribal-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            color: var(--tribal-ocre);
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            position: relative;
        }

        .member-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .member-img-container i {
            z-index: 0;
        }

        .member-card h3 {
            color: var(--tribal-ocre);
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        .member-card .position {
            color: var(--tribal-light);
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1rem;
            display: block;
            border-bottom: 1px dashed rgba(184, 134, 11, 0.4);
            padding-bottom: 0.5rem;
        }

        .member-card .description {
            color: #d4c9b8;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1rem;
            flex: 1;
        }

        .member-card .social-icons {
            margin-top: auto;
            border-top: 1px solid rgba(184, 134, 11, 0.2);
            padding-top: 1rem;
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            font-size: 1.75rem;
        }

        .member-card .social-icons a {
            color: var(--tribal-ocre);
            transition: all 0.3s ease;
        }

        .member-card .social-icons a:hover {
            color: #d4a017;
            transform: scale(1.3);
        }

        /* Founder Photo Top Styles */
        .founder-photo-top {
            text-align: center;
            margin-bottom: 1rem;
        }

        .founder-photo-top .img-container {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid var(--tribal-ocre);
            box-shadow: 0 10px 20px rgba(0,0,0,0.6);
            background: var(--tribal-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--tribal-ocre);
            position: relative;
        }

        .founder-photo-top .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .founder-photo-top .img-container i {
            z-index: 0;
        }

        .founder-photo-top .founder-info {
            margin-top: 1rem;
        }

        .founder-photo-top .founder-info h3 {
            color: var(--tribal-ocre);
            font-size: 1.8rem;
            margin-bottom: 0.3rem;
        }

        .founder-photo-top .founder-info .position {
            color: var(--tribal-light);
            font-size: 1.2rem;
            font-weight: 500;
        }

        /* Founder Section Specific Styles */
        .founder-section .member-card {
            max-width: 400px;
            margin: 0 auto;
            padding: 2rem;
            background: linear-gradient(145deg, rgba(47, 79, 47, 0.3), rgba(28, 20, 8, 0.98));
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8);
        }

        .founder-section .member-img-container {
            width: 160px;
            height: 160px;
            border-width: 5px;
        }

        .founder-section h3 {
            font-size: 1.8rem;
        }

        .founder-section .position {
            font-size: 1.1rem;
        }

        .founder-section .description {
            font-size: 1rem;
        }

        /* Founder Intro Styles */
        .founder-intro {
            font-size: 1.5rem;
            font-weight: bold;
            color: #f0e6d0;
            margin-bottom: 1.5rem;
            margin-top: 0;
            line-height: 1.3;
        }

        /* Section Title Adjustment */
        .section-title {
            margin-bottom: 0.5rem;
        }

        /* Responsive Styles */
        /* Untuk Laptop/Komputer (default di atas) */

        /* Untuk Tablet (antara 769px - 1024px) */
        @media (min-width: 769px) and (max-width: 1024px) {
            .story-content {
                padding: 2rem;
            }
            .team-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 1.5rem;
            }
            .member-card {
                padding: 1.5rem 1.2rem;
            }
            .member-img-container {
                width: 120px;
                height: 120px;
            }
            .member-card h3 {
                font-size: 1.4rem;
            }
            .member-card .position {
                font-size: 0.95rem;
            }
            .member-card .description {
                font-size: 0.9rem;
            }
            .founder-photo-top .img-container {
                width: 180px;
                height: 180px;
            }
            .founder-photo-top .founder-info h3 {
                font-size: 1.6rem;
            }
            .founder-photo-top .founder-info .position {
                font-size: 1.1rem;
            }
            .founder-section .member-card {
                max-width: 350px;
                padding: 1.8rem;
            }
            .founder-section .member-img-container {
                width: 140px;
                height: 140px;
            }
            .founder-intro {
                font-size: 1.4rem;
            }
            .section-title {
                margin-bottom: 0.4rem;
            }
        }

        /* Untuk HP/Tablet Kecil (max 768px) */
        @media (max-width: 768px) {
            .story-content {
                padding: 1.8rem;
            }
            .team-grid {
                grid-template-columns: 1fr;
            }
            .member-card {
                padding: 1.5rem;
            }
            .member-img-container {
                width: 110px;
                height: 110px;
            }
            .member-card h3 {
                font-size: 1.3rem;
            }
            .member-card .position {
                font-size: 0.9rem;
            }
            .member-card .description {
                font-size: 0.85rem;
            }
            .founder-section .member-card {
                padding: 1.8rem;
            }
            .founder-photo-top .img-container {
                width: 160px;
                height: 160px;
            }
            .founder-photo-top .founder-info h3 {
                font-size: 1.5rem;
            }
            .founder-photo-top .founder-info .position {
                font-size: 1rem;
            }
            .founder-intro {
                font-size: 1.3rem;
            }
            .founder-photo-top {
                margin-bottom: 0.8rem;
            }
            .section-title {
                margin-bottom: 0.3rem;
            }
        }

        /* Untuk HP Sangat Kecil (max 480px) */
        @media (max-width: 480px) {
            .story-content {
                padding: 1.5rem;
            }
            .section-title h2 {
                font-size: 1.8rem;
            }
            .section-title p {
                font-size: 1rem;
            }
            .member-card {
                padding: 1.2rem;
            }
            .member-img-container {
                width: 100px;
                height: 100px;
            }
            .member-card h3 {
                font-size: 1.2rem;
            }
            .member-card .position {
                font-size: 0.85rem;
            }
            .member-card .description {
                font-size: 0.8rem;
            }
            .member-card .social-icons {
                font-size: 1.5rem;
                gap: 1rem;
            }
            .founder-photo-top .img-container {
                width: 140px;
                height: 140px;
            }
            .founder-photo-top .founder-info h3 {
                font-size: 1.4rem;
            }
            .founder-photo-top .founder-info .position {
                font-size: 0.95rem;
            }
            .founder-intro {
                font-size: 1.2rem;
            }
            .founder-photo-top {
                margin-bottom: 0.5rem;
            }
            .section-title {
                margin-bottom: 0.2rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- FOUNDER PHOTO AT TOP -->
    <section class="container py-4 founder-photo-top">
        <div class="img-container">
            @if($founder && $founder->foto_profil)
                <img src="{{ asset($founder->foto_profil) }}" alt="{{ $founder->nama ?? 'Founder' }} - Founder" onerror="this.style.display='none';">
            @endif
            <i class="fas fa-user-tie"></i>
        </div>
    </section>

    <!-- OUR STORY -->
    <section class="container py-5 pt-0">
        <div class="section-title">
            <h2>Who we are :</h2>
        </div>
        @if($founder)
            <div class="founder-intro">
                I'm {{ $founder->nama ?? '' }} {{ strtolower($founder->posisi ?? '') }} of Mentawai Tribe
            </div>
        @endif
        <div class="story-content">
            {!! $setting->about_us ?? '<p>Our journey began with a belief: The Mentawai culture cannot be understood from a distance, it must be lived. ...</p>' !!}
        </div>
    </section>

    <!-- MEET OUR TEAM -->
    <section class="container py-5">
        <div class="section-title">
            <h2>Meet our Local experts & team</h2>
        </div>

        <!-- TEKS BARU YANG KAMU MINTA -->
        <div class="story-content" style="margin-bottom: 3rem;">
            <p>Our passionate team of local guides and experts is dedicated to showing you the true beauty of our island. We go beyond the typical tourist spots to immerse you in Sumatra's rich culture, incredible wildlife, and stunning landscapes. From trekking to see orangutans in their natural habitat to discovering traditional villages and tribes, we craft personalized experiences that connect you to the real Sumatra.</p>

            <p>With us, you're not just exploring, you're engaging with local communities and making a positive impact. Let us show you Sumatra through the eyes of those who know and love it best. Come as a visitor, leave as a friend!</p>
        </div>

        <div class="team-grid">
            @forelse($team as $member)
            <div class="member-card">
                <div class="member-img-container">
                    @if($member->foto_profil)
                        <img src="{{ asset($member->foto_profil) }}" alt="{{ $member->nama ?? 'Member' }}" onerror="this.style.display='none';">
                    @endif
                    <i class="fas fa-user"></i>
                </div>
                <h3>{{ $member->nama ?? '' }}</h3>
                <span class="position">{{ $member->posisi ?? '' }}</span>
                @if(!empty($member->deskripsi))
                <div class="description">
                    {{ $member->deskripsi }}
                </div>
                @else
                <div class="description"></div>
                @endif

                @if($member->instagram || $member->twitter || $member->facebook)
                <div class="social-icons">
                    @if($member->instagram)
                        <a href="{{ $member->instagram }}" target="_blank" rel="noopener noreferrer" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if($member->twitter)
                        <a href="{{ $member->twitter }}" target="_blank" rel="noopener noreferrer" title="X / Twitter">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                    @endif
                    @if($member->facebook)
                        <a href="{{ $member->facebook }}" target="_blank" rel="noopener noreferrer" title="Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                    @endif
                </div>
                @endif
            </div>
            @empty
            <p>No team members available.</p>
            @endforelse
        </div>
    </section>
@endsection
