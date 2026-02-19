@extends('layouts.landing.app')
@section('content')
    <!-- Hero Carousel -->
    <section class="hero-carousel" id="home">
        <div class="carousel-slides">
            @forelse($heroSlides as $slide)
                <div class="carousel-slide">
                    <img src="{{ asset($slide->image ?? '') }}"
                        alt="{{ $slide->title ?? '' }}">
                    <div class="slide-content">
                        <h2>{{ $slide->title ?? '' }}</h2>
                        <p>{{ $slide->description ?? '' }}</p>
                        <a href="{{ $slide->button_link ?? '' }}"
                            class="tribal-btn">{{ $slide->button_text ?? '' }}</a>
                    </div>
                </div>
            @empty
                <div class="carousel-slide">
                    <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                        alt="Mentawai Tribal Village">
                    <div class="slide-content">
                        <h2>The Last Mentawai</h2>
                        <p>Discover the indigenous tribe of Mentawai, one of the last remaining tribal cultures in
                            Indonesia, living in harmony with nature for thousands of years.</p>
                        <a href="#culture" class="tribal-btn">Explore Culture</a>
                    </div>
                </div>
            @endforelse
        </div>
        <!-- Carousel Navigation -->
        <button class="carousel-nav prev-slide">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="carousel-nav next-slide">
            <i class="fas fa-chevron-right"></i>
        </button>
        <!-- Carousel Indicators: Dynamic -->
        <div class="carousel-indicators">
            @forelse($heroSlides as $key => $slide)
                <div class="indicator {{ $key === 0 ? 'active' : '' }}" data-index="{{ $key }}"></div>
            @empty
                <div class="indicator active" data-index="0"></div>
            @endforelse
        </div>
    </section>
    <!-- Culture Section -->
    <section class="culture-section" id="culture">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12 mb-lg-0 mb-4">
                    <div class="culture-image">
                        <img src="{{ asset($settings->gambar_pengantar ?? '') }}"
                            alt="Mentawai Family" class="img-fluid rounded">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="culture-content">
                        <h3>Mentawai Tribal Culture</h3>
                        {!! $settings->paragraf_pengantar ?? '' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Activities Carousel (Dynamic from Content with jenis_konten 'aktivitas') -->
    <section id="activities" class="py-5">
        <div class="container-fluid">
            <div class="section-title">
                <h2>Traditional Activities</h2>
            </div>
        </div>
        <div class="activities-carousel">
            <div class="activities-slides">
                @forelse($activities as $activity)
                    <div class="activity-slide">
                        <div class="activity-image">
                            <img src="{{ asset($activity->gambar ?? '') }}"
                                alt="{{ $activity->judul ?? '' }}">
                        </div>
                        <div class="activity-content">
                            <h3>{{ $activity->judul ?? '' }}</h3>
                            <p>{{ Str::limit(strip_tags($activity->ringkasan ?? ''), 150) }}</p>
                            <a href="{{ route('blog.show', ['jenis' => 'aktivitas', 'slug' => $activity->slug ?? '']) }}" class="tribal-btn mt-3">Read More</a>
                        </div>
                    </div>
                @empty
                    <!-- Fallback if no activities in DB -->
                    <div class="activity-slide">
                        <div class="activity-image">
                            <img src="../mentawai/tradional.jpg" alt="Mentawai Weaving">
                        </div>
                        <div class="activity-content">
                            <h3>Traditional Weaving</h3>
                            <p>Mentawai women practice intricate weaving techniques...</p>
                            <a href="#gallery" class="tribal-btn">View Weaving Gallery</a>
                        </div>
                    </div>
                @endforelse
            </div>
            <button class="activity-nav activity-prev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="activity-nav activity-next">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </section>
    <!-- Travel Themes Section (Dynamic from Service type 'tema') -->
    <section id="themes" class="themes-section">
        <div class="container-fluid">
            <div class="section-title">
                <h2>Our Travel Themes</h2>
                <p class="mt-3 text-center" style="max-width: 800px; margin: 0 auto;">Choose a travel experience that
                    matches your interests and needs. Each theme is designed to provide a deep, authentic experience.</p>
            </div>
            <div class="row g-4">
                @forelse($services->where('type', 'tema') as $theme)
                    <div class="col-lg-4 col-md-6">
                        <div class="theme-card">
                            <div class="theme-icon">
                                <i class="{{ $theme->getIconClassAttribute() ?? '' }}"></i>
                            </div>
                            <h3>{{ $theme->title ?? '' }}</h3>
                            <p>{{ $theme->description ?? '' }}</p>
                            <ul class="culture-features icon-list">
                                @foreach ($theme->steps as $step)
                                    <li>
                                        <i class="{{ $step->kategori->icon ?? '' }}"></i>
                                        <span>{{ $step->title ?? '' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @empty
                    <!-- Fallback if no themes in DB -->
                    <div class="col-lg-4 col-md-6">
                        <div class="theme-card">
                            <div class="theme-icon"><i class="fas fa-mountain"></i></div>
                            <h3>Wild Nature Adventure</h3>
                            <p>Exploration of tropical forests...</p>
                            <ul class="culture-features">
                                <li>Trekking to Mentawai forest</li>
                                <li>Camping in the open nature</li>
                            </ul>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Our Services Section (Dynamic from Service type 'layanan') -->
    <section id="services" class="services-section">
        <div class="container-fluid">
            <div class="section-title">
                <h2>Our Services & Facilities</h2>
                <p class="mt-3 text-center" style="max-width: 800px; margin: 0 auto;">We provide complete services to
                    ensure your travel experience is comfortable, safe, and meaningful.</p>
            </div>
            <div class="row">
                @forelse($services->where('type', 'layanan') as $service)
                    <div class="col-lg-6">
                        <div class="service-item">
                            <div class="service-icon">
                                <i class="{{ $service->getIconClassAttribute() ?? '' }}"></i>
                            </div>
                            <h3>{{ $service->title ?? '' }}</h3>
                            <p>{{ $service->description ?? '' }}</p>
                            <ul class="culture-features">
                                @foreach ($service->steps as $step)
                                    <li>{{ $step->title ?? '' }}: {{ $step->description ?? '' }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-6">
                        <div class="service-item">
                            <div class="service-icon"><i class="fas fa-user-tie"></i></div>
                            <h3>Experienced Local Guides</h3>
                            <p>Local guides fluent in Mentawai and Indonesian languages...</p>
                            <ul class="culture-features">
                                <li>Certified guides</li>
                                <li>Cultural translator</li>
                            </ul>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Meet Our Team Section -->
    <section id="team" class="team-section">
        <div class="team-tribal-bg"></div>
        <div class="container-fluid">
            <div class="section-title">
                <h2>Meet Our Team</h2>
                <p class="mt-3 text-center" style="max-width: 800px; margin: 0 auto;">Our team consists of local experts,
                    experienced guides, and dedicated supporters to provide the best experience about Mentawai culture.</p>
            </div>
            <div class="team-carousel-container">
                <button class="team-carousel-nav team-prev" disabled><i class="fas fa-chevron-left"></i></button>
                <button class="team-carousel-nav team-next"><i class="fas fa-chevron-right"></i></button>
                <div class="team-carousel-track">
                    @forelse($teamMembers as $member)
                        <div class="team-card">
                            <div class="team-img-container">
                                <img src="{{ asset($member->foto_profil ?? '') }}"
                                    alt="{{ $member->nama ?? '' }}">
                            </div>
                            <h3>{{ $member->nama ?? '' }}</h3>
                            <span class="position">{{ $member->posisi ?? '' }}</span>
                            <span class="location"><i class="fas fa-map-marker-alt me-1"></i>
                                {{ $member->alamat ?? '' }}</span>
                            <p>{{ $member->deskripsi ?? '' }}</p>
                            <div class="team-social">
                                @if ($member->facebook)
                                    <a href="{{ $member->facebook }}" aria-label="Facebook"><i
                                            class="fab fa-facebook-f"></i></a>
                                @endif
                                @if ($member->instagram)
                                    <a href="{{ $member->instagram }}" aria-label="Instagram"><i
                                            class="fab fa-instagram"></i></a>
                                @endif
                                @if ($member->twitter)
                                    <a href="{{ $member->twitter }}" aria-label="Twitter"><i
                                            class="fab fa-twitter"></i></a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="team-card">
                            <div class="team-img-container">
                                <img src="../mentawai/TeuJarto.jpeg" alt="Teu Jarto">
                            </div>
                            <h3>Teu Jarto</h3>
                            <span class="position">Founder & Head Guide</span>
                            <p>Native Mentawai with 12 years of experience...</p>
                            <div class="team-social">
                                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="team-carousel-indicators"></div>
            </div>
            <div class="mt-5 text-center">
                <a href="#contact" class="tribal-btn">Contact Our Team</a>
            </div>
        </div>
    </section>
       <!-- Gallery Section -->
    <section id="gallery" class="py-5">
        <div class="container-fluid">
            <div class="section-title">
                <h2>Mentawai Tribal Gallery</h2>
            </div>
            <div class="tribal-grid">
                @forelse($galleries as $item)
                    <div class="tribal-item">
                        <img src="{{ asset($item->image ?? '') }}"
                            alt="{{ $item->title ?? '' }}">
                        <div class="item-overlay">
                            <h3>{{ $item->title ?? '' }}</h3>
                            <p>{{ $item->description ?? '' }}</p>
                           
                @if($item->button_text && $item->button_url)
                    <a href="{{ $item->button_url }}"
                       class="btn btn-warning btn-sm mt-2"
                       target="_blank">
                        <i class="fa-solid fa-camera me-1"></i>
                        {{ $item->button_text }}
                    </a>
                @endif
                        </div>
                    </div>
                @empty
                    <div class="tribal-item">
                        <img src="../mentawai/tradional.jpg" alt="Mentawai Tattoo Artist">
                        <div class="item-overlay">
                            <h3>Traditional Tattoo Artist</h3>
                            <p>The skilled tattoo artist creating traditional Mentawai tattoos using natural tools and
                                pigments.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hero Carousel (already dynamic from before)
            const heroSlidesElem = document.querySelector('.carousel-slides');
            const slides = document.querySelectorAll('.carousel-slide');
            const heroIndicators = document.querySelectorAll('.carousel-indicators .indicator');
            const prevBtn = document.querySelector('.prev-slide');
            const nextBtn = document.querySelector('.next-slide');
            let currentHeroSlide = 0;
            let totalHeroSlides = slides.length || 1;
            // ensure container and slides widths match actual number of slides
            if (heroSlidesElem) heroSlidesElem.style.width = `${totalHeroSlides * 100}%`;
            slides.forEach(slide => {
                slide.style.width = `${100 / totalHeroSlides}%`;
                slide.style.flex = `0 0 ${100 / totalHeroSlides}%`;
            });
            function updateHeroCarousel() {
                if (!heroSlidesElem) return;
                heroSlidesElem.style.transform = `translateX(-${currentHeroSlide * (100 / totalHeroSlides)}%)`;
                heroIndicators.forEach((ind, idx) => ind.classList.toggle('active', idx === currentHeroSlide));
            }
            function nextHeroSlide() {
                currentHeroSlide = (currentHeroSlide + 1) % totalHeroSlides;
                updateHeroCarousel();
            }
            function prevHeroSlide() {
                currentHeroSlide = (currentHeroSlide - 1 + totalHeroSlides) % totalHeroSlides;
                updateHeroCarousel();
            }
            if (prevBtn) prevBtn.addEventListener('click', prevHeroSlide);
            if (nextBtn) nextBtn.addEventListener('click', nextHeroSlide);
            heroIndicators.forEach((ind, idx) => {
                ind.addEventListener('click', () => {
                    currentHeroSlide = idx;
                    updateHeroCarousel();
                });
            });
            setInterval(nextHeroSlide, 5000);
            // Activities Carousel (dynamic if loop added later)
            const activitySlidesElem = document.querySelector('.activities-slides');
            const activityIndicators = document.querySelectorAll(
                '.activity-indicators .indicator'); // if indicators added
            const activityPrev = document.querySelector('.activity-prev');
            const activityNext = document.querySelector('.activity-next');
            let currentActivitySlide = 0;
            const totalActivitySlides = document.querySelectorAll('.activity-slide').length || 3;
            function updateActivityCarousel() {
                activitySlidesElem.style.transform =
                    `translateX(-${currentActivitySlide * 100}%)`;
            }
            function nextActivitySlide() {
                currentActivitySlide = (currentActivitySlide + 1) % totalActivitySlides;
                updateActivityCarousel();
            }
            function prevActivitySlide() {
                currentActivitySlide = (currentActivitySlide - 1 + totalActivitySlides) % totalActivitySlides;
                updateActivityCarousel();
            }
            activityPrev.addEventListener('click', prevActivitySlide);
            activityNext.addEventListener('click', nextActivitySlide);
            setInterval(nextActivitySlide, 7000);
            // Team Carousel (remains as original)
            const teamCarouselTrack = document.querySelector('.team-carousel-track');
            const teamCards = document.querySelectorAll('.team-card');
            const teamPrevBtn = document.querySelector('.team-prev');
            const teamNextBtn = document.querySelector('.team-next');
            const teamIndicatorsContainer = document.querySelector('.team-carousel-indicators');
            function getVisibleCardsCount() {
                const container = document.querySelector('.team-carousel-container');
                if (!container) return 1;
                const containerWidth = container.offsetWidth;
                let cardWidth = 280 + 25;
                if (teamCards.length > 0) {
                    const style = window.getComputedStyle(teamCards[0]);
                    cardWidth = teamCards[0].offsetWidth + parseFloat(style.marginLeft) + parseFloat(style
                        .marginRight) + 25;
                }
                return Math.max(1, Math.floor(containerWidth / cardWidth));
            }
            let currentTeamIndex = 0;
            let visibleCardsCount = getVisibleCardsCount();
            const totalTeamCards = teamCards.length;
            function createIndicators() {
                teamIndicatorsContainer.innerHTML = '';
                const totalIndicators = Math.ceil(totalTeamCards / visibleCardsCount);
                if (totalIndicators <= 1) {
                    teamIndicatorsContainer.style.display = 'none';
                    return;
                }
                teamIndicatorsContainer.style.display = 'flex';
                for (let i = 0; i < totalIndicators; i++) {
                    const indicator = document.createElement('div');
                    indicator.className = 'team-indicator';
                    if (i === 0) indicator.classList.add('active');
                    indicator.addEventListener('click', () => {
                        currentTeamIndex = i * visibleCardsCount;
                        updateTeamCarousel();
                    });
                    teamIndicatorsContainer.appendChild(indicator);
                }
            }
            function updateTeamCarousel() {
                if (teamCards.length === 0) return;
                const cardWidth = teamCards[0].offsetWidth + 25;
                teamCarouselTrack.style.transform = `translateX(-${currentTeamIndex * cardWidth}px)`;
                const currentIndicator = Math.floor(currentTeamIndex / visibleCardsCount);
                document.querySelectorAll('.team-indicator').forEach((ind, idx) => ind.classList.toggle('active',
                    idx === currentIndicator));
                teamPrevBtn.disabled = currentTeamIndex === 0;
                teamNextBtn.disabled = currentTeamIndex + visibleCardsCount >= totalTeamCards;
            }
            function nextTeamSlide() {
                if (currentTeamIndex + visibleCardsCount < totalTeamCards) {
                    currentTeamIndex += visibleCardsCount;
                    updateTeamCarousel();
                }
            }
            function prevTeamSlide() {
                if (currentTeamIndex - visibleCardsCount >= 0) {
                    currentTeamIndex -= visibleCardsCount;
                    updateTeamCarousel();
                } else {
                    currentTeamIndex = 0;
                    updateTeamCarousel();
                }
            }
            function initTeamCarousel() {
                visibleCardsCount = getVisibleCardsCount();
                createIndicators();
                updateTeamCarousel();
            }
            teamPrevBtn.addEventListener('click', prevTeamSlide);
            teamNextBtn.addEventListener('click', nextTeamSlide);
            initTeamCarousel();
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(initTeamCarousel, 250);
            });
            let startX = 0,
                endX = 0;
            teamCarouselTrack.addEventListener('touchstart', e => startX = e.touches[0].clientX);
            teamCarouselTrack.addEventListener('touchmove', e => endX = e.touches[0].clientX);
            teamCarouselTrack.addEventListener('touchend', () => {
                const dist = startX - endX;
                if (Math.abs(dist) > 50) {
                    if (dist > 0 && !teamNextBtn.disabled) nextTeamSlide();
                    else if (dist < 0 && !teamPrevBtn.disabled) prevTeamSlide();
                }
            });
            // Smooth scroll & other animations remain
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', e => {
                    e.preventDefault();
                    document.querySelector(anchor.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1
            });
            document.querySelectorAll('.tribal-item, .theme-card, .service-item').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(50px)';
                el.style.transition = 'opacity 0.8s, transform 0.8s';
                observer.observe(el);
            });
            window.addEventListener('scroll', () => {
                const header = document.querySelector('header');
                if (header) header.style.backgroundColor = window.scrollY > 100 ? 'rgba(28, 20, 8, 0.98)' :
                    'rgba(28, 20, 8, 0.95)';
            });
        });
    </script>
@endsection
@section('styles')
    <style>
        .icon-list {
            list-style: none;
            /* remove default bullet */
            padding-left: 0;
        }
        .icon-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 8px;
        }
        .icon-list li i {
            min-width: 20px;
            margin-top: 3px;
            color: #ff9f04;
        }
    </style>
@endsection