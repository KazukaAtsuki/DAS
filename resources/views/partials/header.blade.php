<!--  Header Start -->
<header class="app-header border-bottom shadow-sm" style="background: #ffffff; z-index: 999;">
    <nav class="navbar navbar-expand-xl navbar-light py-0">

      <div class="container-fluid px-4">

          <div class="d-flex align-items-center justify-content-between w-100" style="height: 70px;">

              <!-- 1. BAGIAN KIRI: LOGO -->
              <div class="d-flex align-items-center" style="width: 250px;">
                <a href="{{ route('dashboard') }}" class="text-nowrap logo-img d-flex align-items-center gap-2 text-decoration-none">
                    <div style="position: relative; width: 38px; height: 38px;">
                        <svg width="38" height="38" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="45" height="45" rx="10" fill="#E0F2F1"/>
                        </svg>
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 12H5L8 5L13 19L17 12H22" stroke="#009688" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="8" cy="5" r="1.5" fill="#1A1A1A"/>
                                <circle cx="13" cy="19" r="1.5" fill="#1A1A1A"/>
                            </svg>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <h3 class="m-0 fw-bolder" style="color: #1A1A1A; font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.5px; font-size: 18px; line-height: 1;">DAS</h3>
                        <span class="fw-bold" style="color: #009688; font-size: 10px; letter-spacing: 2px; line-height: 1;">DAS SYSTEM</span>
                    </div>
                </a>
              </div>

              <!-- 2. BAGIAN TENGAH: MENU (FIXED POSITION) -->
              <div class="d-none d-xl-flex position-absolute start-50 translate-middle-x">
                <ul class="navbar-nav flex-row align-items-center gap-1">

                    <style>
                        /* CSS Custom Menu */
                        .nav-custom {
                            font-weight: 700;
                            font-size: 13px !important;
                            color: #5a6a85 !important;
                            padding: 8px 16px !important;
                            border-radius: 50px;
                            transition: all 0.2s ease;
                            display: flex;
                            align-items: center;
                            gap: 6px;
                        }
                        .nav-custom:hover {
                            color: #009688 !important;
                            background-color: #f2fcfb;
                        }
                        .nav-custom.active {
                            color: #ffffff !important;
                            background-color: #009688;
                            box-shadow: 0 4px 10px rgba(0, 150, 136, 0.25);
                        }
                        /* Hapus panah dropdown default */
                        .dropdown-toggle::after { display: none; }

                        /* CSS Custom Dropdown Item */
                        .custom-dd-item {
                            display: flex;
                            align-items: center;
                            gap: 10px;
                            padding: 10px 12px;
                            border-radius: 8px;
                            color: #4a5568;
                            font-weight: 600;
                            font-size: 13px;
                            transition: all 0.2s;
                        }
                        .custom-dd-item i {
                            font-size: 18px;
                            color: #009688; /* Warna Ikon Teal */
                            transition: transform 0.2s;
                        }
                        .custom-dd-item:hover {
                            background-color: #f2fcfb;
                            color: #009688;
                        }
                        .custom-dd-item:hover i {
                            transform: scale(1.1); /* Efek zoom dikit pas hover */
                        }
                    </style>

                    <li class="nav-item">
                        <a class="nav-link nav-custom text-uppercase {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-custom text-uppercase {{ request()->routeIs('logs.*') ? 'active' : '' }}" href="{{ route('logs.index') }}">Logs Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-custom text-uppercase {{ request()->routeIs('rca.*') ? 'active' : '' }}" href="{{ route('rca.index') }}">RCA Records</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-custom text-uppercase {{ request()->routeIs('hourly.*') ? 'active' : '' }}" href="{{ route('hourly.index') }}">Hourly Avg</a>
                    </li>

                    <!-- Dropdown Master Data -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-custom text-uppercase dropdown-toggle {{ request()->is('master*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Master Data <i class="ti ti-chevron-down" style="font-size: 10px;"></i>
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg mt-3 p-2 rounded-3" style="min-width: 220px;">
                            <li>
                                <a class="dropdown-item custom-dd-item" href="{{ route('stack-config.index') }}">
                                    <i class="ti ti-server"></i> Stack Config
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item custom-dd-item" href="{{ route('references.index') }}">
                                    <i class="ti ti-book-2"></i> References
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item custom-dd-item" href="{{ route('units.index') }}">
                                    <i class="ti ti-scale"></i> Units
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item custom-dd-item" href="{{ route('sensor-config.index') }}">
                                    <i class="ti ti-broadcast"></i> Sensor Config
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Dropdown System -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-custom text-uppercase dropdown-toggle {{ request()->is('system*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            System <i class="ti ti-chevron-down" style="font-size: 10px;"></i>
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg mt-3 p-2 rounded-3" style="min-width: 220px;">
                            <li>
                                <a class="dropdown-item custom-dd-item" href="{{ route('global-config.index') }}">
                                    <i class="ti ti-settings"></i> Global Config
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item custom-dd-item" href="{{ route('users.index') }}">
                                    <i class="ti ti-users"></i> User Management
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
              </div>

              <!-- 3. BAGIAN KANAN: NOTIF & PROFIL -->
              <div class="d-flex align-items-center justify-content-end gap-3" style="width: 250px;">

                <!-- Notifikasi -->
                <a class="nav-link nav-icon-hover p-2 rounded-circle hover-bg-light position-relative" href="javascript:void(0)">
                    <i class="ti ti-bell-ringing fs-5 text-dark"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="width: 8px; height: 8px; top: 12px !important; left: 25px !important;"></span>
                </a>

                <!-- Profil User -->
                <div class="dropdown">
                    @php
                        $role = Auth::user()->role;

                        if ($role === 'Administrator') {
                            $avatar = 'user-1.jpg';      // Foto Admin
                            $borderColor = 'border-primary'; // Warna Biru
                            $roleColor = 'text-primary';
                        } else {
                            $avatar = 'user-2.jpg';      // Foto Operator (Pastikan file ini ada di folder public/template/assets/images/profile/)
                            $borderColor = 'border-success'; // Warna Hijau
                            $roleColor = 'text-success';
                        }
                    @endphp

                    <a class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle p-1 ps-2 pe-3 rounded-pill border bg-white hover-shadow transition" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false" style="transition: all 0.3s;">

                      <!-- Gambar Profil Dinamis -->
                      <!-- Ditambah border warna sesuai role -->
                      <img src="{{ asset('template/assets/images/profile/' . $avatar) }}"
                           alt="" width="32" height="32"
                           class="rounded-circle border border-2 {{ $borderColor }}">

                      <div class="d-none d-md-block text-start lh-1 me-1">
                          <p class="mb-0 fw-bold text-dark" style="font-size: 12px;">{{ Auth::user()->name }}</p>
                          <!-- Warna teks role juga dinamis -->
                          <span class="{{ $roleColor }} fw-semibold" style="font-size: 10px;">{{ Auth::user()->role }}</span>
                      </div>

                      <i class="ti ti-caret-down-filled text-muted" style="font-size: 10px;"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up border-0 shadow-lg mt-2 p-2 rounded-3" aria-labelledby="drop2">

                        <!-- Header Dropdown Kecil -->
                        <div class="px-2 py-2 mb-2 bg-light rounded-2 text-center">
                            <h6 class="mb-0 fs-3 fw-bold">{{ Auth::user()->name }}</h6>
                            <span class="badge {{ $role === 'Administrator' ? 'bg-primary' : 'bg-success' }} rounded-pill px-2 py-1" style="font-size: 10px;">
                                {{ Auth::user()->role }}
                            </span>
                        </div>

                        <div class="message-body">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 btn-sm d-flex align-items-center justify-content-center gap-2">
                                    <i class="ti ti-logout"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

              </div>

          </div>
      </div>
    </nav>
</header>