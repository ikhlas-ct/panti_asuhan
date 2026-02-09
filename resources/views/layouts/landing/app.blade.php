<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Wisata Budaya Mentawai yang Etis dan Bertanggung Jawab">
        <meta name="keywords"
            content="Mentawai, Budaya, Wisata, Tradisi, Indonesia, Etnis, Suku, Pulau Mentawai, Pariwisata, Adat Istiadat">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('storage/' . ($settings->logo ?? 'default-logo.png')) }}"
            type="image/x-icon">

        <title>
            @hasSection('title')
                @yield('title') - Mentawai Tribe
            @else
                Mentawai Tribe
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
