{{-- NAVBAR --}}
{{-- $settings di-inject global oleh View::composer di AppServiceProvider --}}
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <div class="brand-icon"><i class="bi bi-heart-fill"></i></div>
            {{ $settings->nama ?? 'TitikKebaikan' }}
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('berita*') ? 'active' : '' }}"
                        href="{{ route('berita') }}">Berita</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('daftar-panti*') ? 'active' : '' }}"
                        href="{{ route('daftar-panti') }}">Daftar Panti</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kerjasama*') ? 'active' : '' }}"
                        href="{{ route('kerjasama') }}">Kerjasama Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}"
                        href="{{ route('tentang') }}">Tentang Kami</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">

                @auth
                    @php $role = auth()->user()->role; @endphp

                    @if ($role === 'admin_dinsos')
                        <a href="{{ route('dinsos.dashboard') }}" class="btn-admin">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @elseif ($role === 'admin_panti')
                        <a href="{{ route('admin_panti.dashboard') }}" class="btn-admin">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @elseif ($role === 'donatur')
                        <a href="{{ route('donatur.dashboard') }}" class="btn-admin">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn-bookmark">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>

                @else
                    <a href="{{ route('login') }}" class="btn-bookmark">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </a>
                    <a href="{{ route('register.donatur') }}" class="btn-admin">
                        <i class="bi bi-person-plus-fill"></i> Daftar
                    </a>
                @endauth

            </div>
        </div>
    </div>
</nav>
