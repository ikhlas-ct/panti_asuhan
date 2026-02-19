<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description"
            content="Explore ethical and responsible Mentawai cultural tourism. Discover Mentawai tribe traditions, customs, and authentic experiences on Mentawai Island, Indonesia. Sustainable tourism for ethnic culture enthusiasts.">
        <meta name="keywords"
            content="Mentawai tourism, Mentawai culture, Mentawai tribe, Mentawai traditions, Mentawai Island, Indonesia tourism, Mentawai ethnic, Mentawai customs, ethical cultural tourism, responsible tourism">
        <meta name="author" content="Mentawai Tribe">
        <meta name="robots" content="index, follow">
        <!-- Open Graph Meta Tags for Social Sharing -->
        <meta property="og:title" content="@yield('title', 'Mentawai Tribe - Ethical and Responsible Mentawai Cultural Tourism')">
        <meta property="og:description"
            content="Explore ethical and responsible Mentawai cultural tourism. Discover Mentawai tribe traditions, customs, and authentic experiences on Mentawai Island, Indonesia.">
        <meta property="og:image" content="{{ asset($settings->logo ?? 'default-image/default-logo.png') }}">
        <meta property="og:url" content="{{ request()->url() }}">
        <meta property="og:type" content="website">
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('title', 'Mentawai Tribe - Ethical and Responsible Mentawai Cultural Tourism')">
        <meta name="twitter:description"
            content="Explore ethical and responsible Mentawai cultural tourism. Discover Mentawai tribe traditions, customs, and authentic experiences on Mentawai Island, Indonesia.">
        <meta name="twitter:image" content="{{ asset($settings->logo ?? 'default-image/default-logo.png') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset($settings->logo ?? 'default-image/default-logo.png') }}"
            type="image/x-icon">
        <link rel="canonical" href="{{ request()->url() }}">
        <title>
            @hasSection('title')
                @yield('title') - Mentawai Tribe: Ethical Cultural Tourism on Mentawai Island
            @else
                Mentawai Tribe: Ethical and Responsible Cultural Tourism on Mentawai Island, Indonesia
            @endif
        </title>
        <!-- Bootstrap 5.3 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('landing/css/main.css') }}">
        @yield('styles')
    </head>

    <body>
        <!-- Tribal Background Pattern -->
        <div class="tribal-bg"></div>

        <!-- Header dengan Bootstrap Navbar Responsif -->
        <header>
            @include('partials.landing.header')
        </header>
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer id="contact">
            @include('partials.landing.footer')
        </footer>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->nomor_telepon ?? '6281261513662') }}"
            class="whatsapp-float" target="_blank" aria-label="WhatsApp Konsultasi">
            <i class="fab fa-whatsapp"></i>
        </a>

        <!-- Bootstrap 5.3 JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        @yield('scripts')

    </body>

</html>
