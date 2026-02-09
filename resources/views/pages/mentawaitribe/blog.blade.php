@extends('layouts.landing.app')
@section('title', ucfirst($jenis) . ' Mentawai Culture')
@section('content')
    <section class="blog-hero">
        <div class="container">
            @if ($jenis == 'artikel')
                <h1>Mentawai Culture Blog</h1>
                <p>Discover stories, traditions, and daily life of the Mentawai Tribe through in-depth writings from cultural writers and researchers.</p>
            @elseif ($jenis == 'aktivitas')
                <h1>Mentawai Cultural Activities</h1>
                <p>Explore various traditional and modern activities that reflect the daily life of the Mentawai Tribe, from ceremonies to community activities.</p>
            @elseif ($jenis == 'ethical')
                <h1>Mentawai Cultural Ethics</h1>
                <p>Understand the ethical principles, values, and moral guidelines firmly held by the Mentawai Tribe in maintaining harmony with nature and fellow beings.</p>
            @endif
            <a href="#featured" class="tribal-btn">Start Reading</a>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="blog-content">
        <div class="container">
            <div class="section-title" id="featured">
                <h2>Latest {{ ucfirst($jenis) }}</h2>
                <p>In-depth exploration of the culture, traditions, and life of the Mentawai Tribe</p>
            </div>

            <div class="row">
                <!-- Main Blog Content -->
                <div class="col-lg-8 col-md-7">
                    <div class="row">
                        @forelse ($kontens as $konten)
                            <!-- Dynamic Blog Post -->
                            <div class="col-lg-6 col-md-12 mb-4">
                                <article class="blog-card">
                                    <img src="{{ asset('storage/' . $konten->gambar) }}" alt="{{ $konten->judul }}" class="blog-card-img">
                                    <div class="blog-card-body">
                                        <span class="blog-card-category">{{ $konten->kategori->nama_kategori }}</span>
                                        <h3 class="blog-card-title">{{ $konten->judul }}</h3>
                                        <p class="blog-card-text">{{ $konten->ringkasan ?? Str::limit(strip_tags($konten->isi), 150) }}</p>
                                        <div class="blog-card-meta">
                                            <div class="blog-card-author">
                                                <img src="{{ asset('storage/' . ($konten->user->pegawai->foto_profil ?? 'https://randomuser.me/api/portraits/men/32.jpg')) }}" alt="Author" class="author-avatar">
                                                <span>{{ $konten->user->pegawai->nama }}</span>
                                            </div>
                                            <span>{{ $konten->tanggal_publikasi->format('d F Y') }}</span>
                                        </div>
                                        <a href="{{ route('blog.show', ['jenis' => $jenis, 'slug' => $konten->slug]) }}" class="tribal-btn mt-3">Read More</a> <!-- Assuming route named blog.show with parameters jenis and slug -->
                                    </div>
                                </article>
                            </div>
                        @empty
                            <p>No {{ $jenis }} available at this time.</p>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Blog pagination" class="blog-pagination">
                        {{ $kontens->links('pagination::bootstrap-4') }} <!-- Use Laravel pagination -->
                    </nav>
                </div>

                <div class="col-lg-4 col-md-5">
                    <div class="sidebar-widget">
                        @if ($jenis == 'artikel')
                            <h3 class="widget-title">Article Categories</h3>
                        @elseif ($jenis == 'aktivitas')
                            <h3 class="widget-title">Activity Categories</h3>
                        @elseif ($jenis == 'ethical')
                            <h3 class="widget-title">Ethical Categories</h3>
                        @endif
                        <ul class="categories-list">
                            @foreach ($kategoris as $kategori)
                                <li>
                                    <a href="{{ route('blog.category', ['jenis' => $jenis, 'slug' => $kategori->slug]) }}"> <!-- Assuming route named blog.category with parameters jenis and slug -->
                                        {{ $kategori->nama_kategori }}
                                        <span class="count">{{ $kategori->konten()->where('jenis_konten', $jenis)->count() }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- About Widget -->
                    <div class="sidebar-widget">
                        @if ($jenis == 'artikel')
                            <h3 class="widget-title">About This Blog</h3>
                            <p>This blog is dedicated to documenting and preserving the cultural richness of the Mentawai Tribe. Each article is written with in-depth research and respect for the original traditions.</p>
                            <p>We are committed to presenting accurate and beneficial information about the life, traditions, and local wisdom of Mentawai.</p>
                        @elseif ($jenis == 'aktivitas')
                            <h3 class="widget-title">About These Activities</h3>
                            <p>This page is dedicated to showcasing various cultural activities of the Mentawai Tribe. Each activity description is created with in-depth research and respect for the original practices.</p>
                            <p>We are committed to presenting accurate and beneficial information about the activities, traditions, and daily life of Mentawai.</p>
                        @elseif ($jenis == 'ethical')
                            <h3 class="widget-title">About This Ethics</h3>
                            <p>This page is dedicated to discussing the ethical principles and cultural values of the Mentawai Tribe. Each ethics content is created with in-depth research and respect for the original norms.</p>
                            <p>We are committed to presenting accurate and beneficial information about the ethics, morals, and local wisdom of Mentawai.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for navigation
            document.querySelectorAll('nav a, .tribal-btn').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');

                    if (href.startsWith('#')) {
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

            // Blog cards animation on scroll
            const blogCards = document.querySelectorAll('.blog-card');
            const sidebarWidgets = document.querySelectorAll('.sidebar-widget');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            blogCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            sidebarWidgets.forEach(widget => {
                widget.style.opacity = '0';
                widget.style.transform = 'translateY(20px)';
                widget.style.transition = 'opacity 0.6s ease 0.2s, transform 0.6s ease 0.2s';
                observer.observe(widget);
            });

            // Navbar background change on scroll
            window.addEventListener('scroll', function() {
                const header = document.querySelector('header');
                if (window.scrollY > 100) {
                    header.style.backgroundColor = 'rgba(28, 20, 8, 0.98)';
                } else {
                    header.style.backgroundColor = 'rgba(28, 20, 8, 0.95)';
                }
            });

            // Active navigation link highlight
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

            navLinks.forEach(link => {
                const linkHref = link.getAttribute('href');
                if (currentPage === linkHref || (currentPage === '' && linkHref === 'index.html')) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // Mobile touch improvements
            if ('ontouchstart' in window) {
                // Add touch feedback for cards
                blogCards.forEach(card => {
                    card.addEventListener('touchstart', function() {
                        this.style.transition = 'transform 0.1s';
                        this.style.transform = 'scale(0.98)';
                    });

                    card.addEventListener('touchend', function() {
                        this.style.transform = 'scale(1)';
                        this.style.transition = 'transform 0.3s, box-shadow 0.3s';
                    });
                });

                // Improve button touch feedback
                const buttons = document.querySelectorAll('.tribal-btn, .page-link, .categories-list a');
                buttons.forEach(button => {
                    button.addEventListener('touchstart', function() {
                        this.style.opacity = '0.8';
                    });

                    button.addEventListener('touchend', function() {
                        this.style.opacity = '1';
                    });
                });
            }

            // Device detection and logging (for debugging)
            const ua = navigator.userAgent;
            const deviceType =
                /Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua) ? 'Mobile' :
                /Tablet|iPad|PlayBook|Silk|Android(?!.*Mobile)/.test(ua) ? 'Tablet' : 'Desktop';

            console.log(`Device detected: ${deviceType}, Screen width: ${window.innerWidth}px`);
        });

        // Handle window resize for responsive adjustments
        window.addEventListener('resize', function() {
            // Update any responsive elements if needed
            const currentWidth = window.innerWidth;
            console.log(`Window resized to: ${currentWidth}px`);
        });
    </script>
@endsection
