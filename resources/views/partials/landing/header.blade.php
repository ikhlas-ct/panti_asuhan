    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <div class="logo">
                @if ($settings->logo ?? false)
                    <img src="{{ !empty($settings->logo) && file_exists(public_path($settings->logo))
                        ? asset($settings->logo)
                        : asset('images/default-logo.png') }}"
                        alt="{{ $settings->nama ?? 'MENTAWAI ETHICAL TOURS' }}" class="logo-image"
                        style="max-height: 60px; margin-right: 10px;">
                @else
                    <div class="tribal-symbol"></div>
                @endif
                <div class="logo-text">
                    <h1>{{ $settings->nama ?? 'MENTAWAI ETHICAL TOURS' }}</h1>
                    <span>{{ $settings->slogan ?? 'SUSTAINABLE TOURISM' }}</span>
                </div>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <div class="navbar-collapse justify-content-end collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.index') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.blog', ['jenis' => 'artikel']) }}">Articles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.blog', ['jenis' => 'aktivitas']) }}">Activities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.ethical') }}">Ethical Tourism</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.transportasi') }}">Transportation</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.contact') }}">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
