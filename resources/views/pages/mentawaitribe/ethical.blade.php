@extends('layouts.landing.app')
@section('title', 'Ethical Tourism Mentawai')
@section('content')
    <!-- Ethical Tourism Hero Section -->
    <section class="ethical-hero">
        <div class="container">
            <div class="ethical-badge">
                <i class="fas fa-leaf"></i> {{ $setting->slogan ?? '100% SUSTAINABLE TOURISM' }}
            </div>
            <h1>Ethical Tourism Mentawai</h1>
            <p>Explore the beauty of Mentawai culture in a responsible way. Every journey is designed to support local communities, preserve culture, and protect the environment.</p>
            <a href="#packages" class="tribal-btn">View Tour Packages</a>
        </div>
    </section>

    <!-- Ethical Principles Section -->
    <section class="principles-section">
        <div class="container">
            <div class="section-title">
                <h2>Our Ethical Principles</h2>
                <p>We are committed to organizing responsible and sustainable tourism</p>
            </div>

            <div class="principles-grid">
                @foreach ($principles as $principle)
                    <div class="principle-card">
                        <div class="principle-icon">
                            <i class="{{ $principle->kategori->icon ?? 'fas fa-question' }}"></i>
                        </div>
                        <h3>{{ $principle->title }}</h3>
                        <p>{{ $principle->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Tour Packages Section -->
    <section class="packages-section" id="packages">
        <div class="container">
            <div class="section-title">
                <h2>Ethical Tour Packages</h2>
                <p>Choose an experience that matches your interests and time</p>
            </div>

            <div class="row">
                @foreach ($ethicals as $ethical)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="tour-card">
                            <div class="tour-header">
                                @if ($ethical->badge)
                                    <div class="tour-badge badge-{{ strtolower($ethical->badge) }}">{{ $ethical->badge }}
                                    </div>
                                @endif
                                <h3 class="tour-title">{{ $ethical->judul }}</h3>
                                <div class="tour-duration">
                                    <i class="far fa-calendar"></i> {{ $ethical->duration ?? 'Custom Duration' }}
                                </div>
                            </div>
                            <img src="{{ asset('storage/' . $ethical->gambar) }}" alt="{{ $ethical->judul }}"
                                class="tour-image">
                            <div class="tour-body">
                                <p class="tour-description">{{ $ethical->ringkasan }}</p>
                                <div class="tour-footer">
                                    <div class="tour-price">
                                        {{ $ethical->price ?? 'Custom' }} <span>/person</span>
                                    </div>
                                    <div class="tour-actions">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->nomor_telepon ?? '6281261513662') }}?text={{ urlencode('Hello, I am interested in the package: ' . $ethical->judul . '. Please provide more details.') }}"
                                            class="tribal-btn btn-small" target="_blank">
                                            <i class="fas fa-calendar-check"></i> Book Now
                                        </a>

                                        <a href="{{ route('blog.show', ['jenis' => 'ethical', 'slug' => $ethical->slug]) }}"
                                            class="tribal-btn btn-small">
                                            <i class="fas fa-book-open"></i> Read More
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $ethicals->links() }} <!-- Pagination if more than 6 -->
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="why-choose-section">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose Us?</h2>
                <p>{{ $setting->why_choose_us ?? 'Advantages that make your experience different' }}</p>
            </div>

            <div class="benefits-grid">
                @foreach ($benefits as $benefit)
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="{{ $benefit->kategori->icon ?? 'fas fa-question' }}"></i>
                        </div>
                        <div class="benefit-content">
                            <h3>{{ $benefit->title }}</h3>
                            <p>{{ $benefit->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Booking Modal Logic
            const bookingModal = document.getElementById('bookingModal');
            if (bookingModal) {
                bookingModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const tourName = button.getAttribute('data-tour');
                    const modalTourInput = bookingModal.querySelector('#tourPackage');
                    modalTourInput.value = tourName;

                    // Update modal title
                    const modalTitle = bookingModal.querySelector('.modal-title');
                    modalTitle.textContent = `Booking: ${tourName}`;
                });
            }

            // Booking Form Submission
            const bookingForm = document.getElementById('bookingForm');
            if (bookingForm) {
                bookingForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get form data
                    const formData = {
                        tourPackage: document.getElementById('tourPackage').value,
                        participants: document.getElementById('participants').value,
                        startDate: document.getElementById('startDate').value,
                        duration: document.getElementById('duration').value,
                        fullName: document.getElementById('fullName').value,
                        email: document.getElementById('email').value,
                        phone: document.getElementById('phone').value,
                        country: document.getElementById('country').value,
                        specialRequests: document.getElementById('specialRequests').value
                    };

                    // In a real application, you would send this data to a server
                    // For demo, we'll just show a success message
                    const modal = bootstrap.Modal.getInstance(bookingModal);
                    modal.hide();

                    // Show success alert
                    setTimeout(() => {
                        alert(
                            `Thank you ${formData.fullName}!\n\nWe have received your booking request for "${formData.tourPackage}".\nOur team will contact you at ${formData.email} or ${formData.phone} within 24 business hours.`
                        );
                        bookingForm.reset();
                    }, 500);
                });
            }

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

            // Cards animation on scroll
            const tourCards = document.querySelectorAll('.tour-card');
            const principleCards = document.querySelectorAll('.principle-card');
            const benefitItems = document.querySelectorAll('.benefit-item');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1
            });

            tourCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            principleCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });

            benefitItems.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(item);
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

            // Set minimum date for booking (today)
            const today = new Date().toISOString().split('T')[0];
            const startDateInput = document.getElementById('startDate');
            if (startDateInput) {
                startDateInput.min = today;

                // Set default date to 30 days from now
                const defaultDate = new Date();
                defaultDate.setDate(defaultDate.getDate() + 30);
                startDateInput.value = defaultDate.toISOString().split('T')[0];
            }

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
                tourCards.forEach(card => {
                    card.addEventListener('touchstart', function() {
                        this.style.transition = 'transform 0.1s';
                        this.style.transform = 'scale(0.98)';
                    });

                    card.addEventListener('touchend', function() {
                        this.style.transform = 'scale(1)';
                        this.style.transition = 'transform 0.3s, box-shadow 0.3s';
                    });
                });
            }
        });
    </script>
@endsection
</body>

</html>
