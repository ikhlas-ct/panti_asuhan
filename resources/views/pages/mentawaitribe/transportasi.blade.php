@extends('layouts.landing.app')
@section('title', 'Mentawai Tourism Transportation')
@section('content')
    <!-- Hero Section -->
    <section class="transportation-hero">
        <div class="container">
            <h1 class="transportation-title">Transportation & Speedboat Charter</h1>
            <p class="transportation-subtitle">
                Explore the beauty of the Mentawai Islands with our best transportation services.
                From fast speedboats to traditional ships, we are ready to take you to your dream destination.
            </p>
            <a href="#services" class="tribal-btn">
                <i class="fas fa-ship"></i> View Services
            </a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="transportation-container" id="services">
        <h2 class="section-title">Our Transportation Services</h2>

        <div class="transportation-grid" id="transportation-grid">
            @forelse ($transportations as $transportation)
                <div class="transportation-card"
                    data-category="{{ Str::slug($transportation->kategori->nama_kategori ?? 'uncategorized') }}">
                    <img src="{{ $transportation->gambar ? asset($transportation->gambar) : '../mentawai/trans.jpg' }}"
                        alt="{{ $transportation->title }}" class="card-image">
                    <div class="card-content">
                        <span class="card-category">{{ $transportation->kategori->nama_kategori ?? 'Uncategorized' }}</span>
                        <h3 class="card-title">{{ $transportation->title }}</h3>
                        <p>{{ $transportation->description }}</p>

                        <ul class="card-features">
                            @foreach ($transportation->steps as $step)
                                <li><i class="{{ $step->getIconClassAttribute() }}"></i> {{ $step->title }}</li>
                            @endforeach
                        </ul>

                        <div class="card-price"> {{ $transportation->price ?? 0 }}</div>

                        <div class="card-actions">
                            <a href="https://wa.me/{{ $setting->nomor_telepon ?? '+6281261513662' }}?text=Hello,%20I%20want%20to%20book%20{{ urlencode($transportation->title) }}%20with%20price%20Rp%20{{ number_format($transportation->harga ?? 0) }}"
                                class="tribal-btn" style="display: inline-block;">
                                <i class="fab fa-whatsapp me-2"></i> Book Now
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">No transportation services available at this time.</p>
            @endforelse
        </div>
    </section>

    <!-- Info Section -->
    <section class="transportation-container">
        <div class="info-section">
            <h3 class="info-title">Important Information</h3>
            <ul class="info-list">
                @forelse ($informasis as $informasi)
                    <li><i class="{{ $informasi->getIconClassAttribute() }}"></i> {{ $informasi->title }}:
                        {{ $informasi->description }}</li>
                @empty
                    <li><i class="fas fa-info-circle"></i> No additional information at this time.</li>
                @endforelse
            </ul>
        </div>
    </section>

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Smooth scrolling
                document.querySelectorAll('nav a, .tribal-btn').forEach(anchor => {
                    anchor.addEventListener('click', function(e) {
                        const href = this.getAttribute('href');

                        if (href && href.startsWith('#')) {
                            e.preventDefault();
                            const targetId = href.substring(1);
                            const targetElement = document.getElementById(targetId);

                            if (targetElement) {
                                const offsetTop = targetElement.offsetTop - 80;
                                window.scrollTo({
                                    top: offsetTop,
                                    behavior: 'smooth'
                                });

                                // Collapse mobile navbar after clicking link
                                const navbarToggler = document.querySelector('.navbar-toggler');
                                const navbarCollapse = document.querySelector('.navbar-collapse');
                                if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                                    navbarToggler.click();
                                }
                            }
                        }
                    });
                });

                window.addEventListener('scroll', function() {
                    const header = document.querySelector('header');
                    if (window.scrollY > 100) {
                        header.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
                    } else {
                        header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                    }
                });
            });
        </script>
    @endsection
@endsection
