@extends('layouts.landing.app')
@section('title', $konten->judul)
@section('content')
    <!-- Article Detail -->
    <article>
        <section class="article-hero">
            <div class="container">
                <div class="article-header">
                    <span class="article-category">{{ $konten->kategori->nama_kategori ?? 'Uncategorized' }}</span>
                    <h1 class="article-title">{{ $konten->judul }}</h1>
                    <div class="article-meta">
                        <div class="article-author">
                            <img src="{{ asset('storage/' . ($konten->user->pegawai->foto_profil ?? 'https://randomuser.me/api/portraits/women/44.jpg')) }}" alt="{{ $konten->user->pegawai->nama ?? 'Author' }}" class="article-author-img">
                            <span>{{ $konten->user->pegawai->nama ?? 'Author' }}</span>
                        </div>
                        <span>{{ $konten->tanggal_publikasi->format('d F Y') }}</span>
                        <span>{{ $readTime }} min read</span>
                        <span>Views: {{ $konten->viewer ?? '0' }}</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="article-container">
            <img src="{{ asset('storage/' . $konten->gambar) }}" alt="{{ $konten->judul }}" class="article-image">

            <div class="article-content">
                {!! $konten->isi !!}
            </div>

            <!-- Article Navigation -->
            <div class="article-navigation">
                @if ($previous)
                    <a href="{{ route('blog.show', ['jenis' => $jenis, 'slug' => $previous->slug]) }}" class="nav-btn">
                        <i class="fas fa-arrow-left"></i> Previous {{ ucfirst($jenis) }}
                    </a>
                @else
                    <span class="nav-btn disabled"><i class="fas fa-arrow-left"></i> Previous {{ ucfirst($jenis) }}</span>
                @endif
                @if ($next)
                    <a href="{{ route('blog.show', ['jenis' => $jenis, 'slug' => $next->slug]) }}" class="nav-btn">
                        Next {{ ucfirst($jenis) }} <i class="fas fa-arrow-right"></i>
                    </a>
                @else
                    <span class="nav-btn disabled">Next {{ ucfirst($jenis) }} <i class="fas fa-arrow-right"></i></span>
                @endif
            </div>

            <!-- Back Button -->
            <div class="text-center back-to-blog">
                <a href="{{ route('landing.blog', ['jenis' => $jenis]) }}" class="tribal-btn">
                    <i class="fas fa-arrow-left"></i> Back to {{ ucfirst($jenis) }} List
                </a>
            </div>
        </section>
    </article>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for navigation
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

                            // Collapse navbar mobile after clicking link
                            const navbarToggler = document.querySelector('.navbar-toggler');
                            const navbarCollapse = document.querySelector('.navbar-collapse');
                            if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                                navbarToggler.click();
                            }
                        }
                    }
                });
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

            // Gallery image click zoom effect
            const galleryImages = document.querySelectorAll('.gallery-img');
            galleryImages.forEach(img => {
                img.addEventListener('click', function() {
                    const src = this.getAttribute('src');
                    const modal = `
                        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content bg-transparent border-0">
                                    <div class="modal-body p-0">
                                        <img src="${src}" class="img-fluid rounded" alt="Gallery Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Remove existing modal
                    const existingModal = document.getElementById('imageModal');
                    if (existingModal) {
                        existingModal.remove();
                    }

                    // Add new modal
                    document.body.insertAdjacentHTML('beforeend', modal);
                    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                    imageModal.show();
                });
            });
        });
    </script>
@endsection
