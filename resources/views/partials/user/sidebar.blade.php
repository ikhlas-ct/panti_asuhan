<!-- Sidebar -->
<div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="{{ $settings?->logo ? asset($settings->logo) : asset('default-image/default-logo.png') }}"
                alt="navbar brand" class="navbar-brand" height="50" />
            <span class="ms-2 text-white">{{ $settings->nama ?? 'Nama Website' }}</span>
        </a>

        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
            </button>
        </div>
        <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
        </button>
    </div>
    <!-- End Logo Header -->
</div>
<div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
        <ul class="nav nav-secondary">

            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Profile -->
            <li class="nav-item">
                <a href="{{ route('pegawai.profil') }}">
                    <i class="fas fa-user"></i>
                    <p>Profile</p>
                </a>
            </li>

            <li class="nav-section">
                <span class="sidebar-mini-icon">
                    <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Komponen</h4>
            </li>

            <!-- Website Setting -->
            <li class="nav-item">
                <a href="{{ route('camat.settings.edit') }}">
                    <i class="fas fa-cogs"></i>
                    <p>Website Setting</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kategori.index') }}">
                    <i class="fas fa-tags"></i>
                    <p>Kategori</p>
                </a>
            </li>


            <!-- Website Profil -->
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#base">
                    <i class="fas fa-globe"></i>
                    <p>Landing Page</p>
                    <span class="caret"></span>
                </a>
                <div class="collapse" id="base">
                    <ul class="nav nav-collapse">
                        <li>
                            <a href="{{ route('camat.settings.heroslide') }}">
                                <span class="sub-item">Hero Slide</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('camat.pengantar') }}">
                                <span class="sub-item">Kata Pengantar</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('service.index', 'tema') }}">
                                <span class="sub-item">tema</span>
                            </a>
                        </li>
                            <li>
                            <a href="{{ route('service.index', 'layanan') }}">
                                <span class="sub-item">Layanan Aktivitas</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#ethical">
                    <i class="fas fa-balance-scale"></i>
                    <p>Ethical</p>

                    <span class="caret"></span>
                </a>
                <div class="collapse" id="ethical">
                    <ul class="nav nav-collapse">
                        <li>
                            <a href="{{ route('konten.index', 'ethical') }}">
                                <span class="sub-item"> Blog</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('service.index', 'etika') }}">
                                <span class="sub-item">Etika</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('service.index', 'keunggulan') }}">
                                <span class="sub-item">keunggulan</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#transportasi">
                    <i class="fas fa-bus"></i>
                    <p>Transportasi</p>

                    <span class="caret"></span>
                </a>
                <div class="collapse" id="transportasi">
                    <ul class="nav nav-collapse">
                        <li>
                            <a href="{{ route('service.index', 'transportasi') }}">
                                <span class="sub-item">Transportasi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('service.index', 'informasi') }}">
                                <span class="sub-item">Informasi</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="{{ route('konten.index', 'artikel') }}">
                    <i class="fas fa-newspaper"></i>
                    <p>Artikel</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('konten.index', 'aktivitas') }}">
                    <i class="fas fa-calendar-check"></i>
                    <p>Aktivitas</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('service.index', 'transportasi') }}">
                    <i class="fas fa-bus"></i>
                    <p>Transportasi</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('pegawai.index') }}">
                    <i class="fas fa-users"></i>
                    <p>Team</p>
                </a>
            </li>

            <!-- Logout -->
            <li class="nav-item">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>

        </ul>
    </div>
</div>
