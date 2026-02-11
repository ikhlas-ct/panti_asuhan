<div class="main-header-logo">
    <div class="logo-header" data-background-color="dark">
        <a href="{{ route('dashboard') }}" class="logo">
            <img src="{{ !empty($settings->logo) && file_exists(public_path($settings->logo))
                ? asset($settings->logo)
                : asset('default-image/default-logo.png') }}" alt="navbar brand" class="navbar-brand"
                height="20" />
        </a>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
            <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
        </div>
        <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
    </div>
</div>

<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">
        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="{{ !empty(Auth::user()->pegawai?->foto_profil) && file_exists(public_path(Auth::user()->pegawai->foto_profil))
                            ? asset(Auth::user()->pegawai->foto_profil)
                            : asset('default-image/default-user.png') }}"
                            alt="Profile" class="avatar-img rounded-circle" />

                    </div>

                    <span class="profile-username">
                        <span class="op-7">Hi,</span>
                        <span class="fw-bold">{{ Auth::user()->pegawai?->nama ?? 'Pegawai' }}</span>
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <img src="{{ !empty(Auth::user()->pegawai?->foto_profil) && file_exists(public_path(Auth::user()->pegawai->foto_profil))
                                        ? asset(Auth::user()->pegawai->foto_profil)
                                        : asset('default-image/default-user.png') }}"
                                        alt="Profile" class="avatar-img rounded-circle" />

                                </div>
                                <div class="u-text">
                                    <h4>{{ Auth::user()->pegawai?->nama ?? 'Pegawai' }}</h4>
                                    <p class="text-muted">
                                        {{ Auth::user()->pegawai?->email ?? (Auth::user()->email ?? 'Tidak ada email') }}
                                    </p>
                                    <a href="{{ route('pegawai.profil') }}" class="btn btn-xs btn-secondary btn-sm">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                Dashboard
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
