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
                        /* --- CSS NAVIGASI BARU (STYLE GARIS BAWAH) --- */
                        .nav-custom {
                            font-weight: 700;
                            font-size: 14px !important; /* Ukuran font sedikit diperbesar agar jelas */
                            color: #5a6a85 !important;
                            padding: 22px 10px !important; /* Padding atas bawah disesuaikan dengan tinggi navbar */
                            transition: all 0.2s ease;
                            display: flex;
                            align-items: center;
                            gap: 6px;
                            border-bottom: 3px solid transparent; /* Garis transparan default */
                            border-radius: 0; /* Hapus lengkungan */
                        }

                        /* Efek Hover */
                        .nav-custom:hover {
                            color: #009688 !important;
                            background-color: transparent; /* Tidak ada background saat hover */
                        }

                        /* Efek Aktif (Garis Bawah Hijau) */
                        .nav-custom.active {
                            color: #009688 !important; /* Teks jadi Hijau Teal */
                            background-color: transparent; /* Hapus background blok */
                            box-shadow: none; /* Hapus bayangan */
                            border-bottom: 3px solid #009688; /* Munculkan Garis Bawah */
                        }

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
                            color: #009688;
                            transition: transform 0.2s;
                        }
                        .custom-dd-item:hover {
                            background-color: #f2fcfb;
                            color: #009688;
                        }
                        .custom-dd-item:hover i {
                            transform: scale(1.1);
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

                    <li class="nav-item dropdown">
                        <a class="nav-link nav-custom text-uppercase dropdown-toggle {{ request()->is('master*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Master Data <i class="ti ti-chevron-down" style="font-size: 10px;"></i>
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg mt-0 p-2 rounded-bottom-3" style="min-width: 220px;">
                            <li><a class="dropdown-item custom-dd-item" href="{{ route('stack-config.index') }}"><i class="ti ti-server"></i> Stack Config</a></li>
                            <li><a class="dropdown-item custom-dd-item" href="{{ route('sensor-config.index') }}"><i class="ti ti-broadcast"></i> Sensor Config</a></li>
                            <li><a class="dropdown-item custom-dd-item" href="{{ route('references.index') }}"><i class="ti ti-book-2"></i> References</a></li>
                            <li><a class="dropdown-item custom-dd-item" href="{{ route('units.index') }}"><i class="ti ti-scale"></i> Units</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link nav-custom text-uppercase dropdown-toggle {{ request()->is('system*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            System <i class="ti ti-chevron-down" style="font-size: 10px;"></i>
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg mt-0 p-2 rounded-bottom-3" style="min-width: 220px;">
                            <li><a class="dropdown-item custom-dd-item" href="{{ route('global-config.index') }}"><i class="ti ti-settings"></i> Global Config</a></li>
                            <li><a class="dropdown-item custom-dd-item" href="{{ route('users.index') }}"><i class="ti ti-users"></i> User Management</a></li>
                        </ul>
                    </li>

                </ul>
              </div>

              <!-- 3. BAGIAN KANAN: NOTIF & PROFIL -->
              <div class="d-flex align-items-center justify-content-end gap-2" style="width: 250px;">

                <!-- Notifikasi Dropdown -->
                <li class="nav-item dropdown me-2 list-unstyled">
                    <a class="nav-link nav-icon-hover d-flex align-items-center justify-content-center rounded-circle" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px; transition: 0.2s;">
                      <style>
                          .nav-icon-hover:hover { background-color: #f3f4f6; }
                          .nav-icon-hover:hover i { color: #009688 !important; }
                      </style>
                      <div class="position-relative d-inline-block">
                          <i class="ti ti-bell-ringing" style="font-size: 1.3rem; color: #5a6a85; transition: color 0.2s;"></i>
                          @if(count($notifications) > 0)
                            <span class="position-absolute bg-primary rounded-circle border border-white"
                                  style="width: 8px; height: 8px; top: 0; right: 0;">
                            </span>
                          @endif
                      </div>
                    </a>

                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up shadow-lg border-0 rounded-4 mt-2" aria-labelledby="drop2" style="min-width: 320px;">
                      <div class="d-flex align-items-center justify-content-between py-3 px-4 border-bottom">
                        <h5 class="mb-0 fs-4 fw-bold text-dark">User Logs</h5>
                        <span class="badge bg-light-primary text-primary rounded-pill px-3 py-1 fs-2 fw-semibold">{{ count($notifications) }} New</span>
                      </div>
                      <div class="message-body" data-simplebar style="max-height: 350px; overflow-y: auto;">
                        @forelse($notifications as $log)
                            <a href="javascript:void(0)" class="py-3 px-4 d-flex align-items-start dropdown-item border-bottom border-light">
                              <div class="position-relative me-3 mt-1">
                                  @php
                                      $icon = 'ti-edit';
                                      $bgColor = '#E0F2F1'; $textColor = '#009688';

                                      if($log->action == 'CREATE') { $icon = 'ti-plus'; $bgColor = '#E0F2F1'; $textColor = '#009688'; }
                                      elseif($log->action == 'DELETE') { $icon = 'ti-trash'; $bgColor = '#FFEBEE'; $textColor = '#D32F2F'; }
                                      elseif($log->action == 'LOGIN') { $icon = 'ti-login'; $bgColor = '#E0F2F1'; $textColor = '#009688'; }
                                      elseif($log->action == 'LOGOUT') { $icon = 'ti-logout'; $bgColor = '#FFF3E0'; $textColor = '#F57C00'; }
                                  @endphp

                                  <div class="rounded-circle d-flex align-items-center justify-content-center"
                                       style="width: 40px; height: 40px; background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                      <i class="ti {{ $icon }} fs-5"></i>
                                  </div>
                              </div>
                              <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold fs-3 text-dark">{{ $log->user->name ?? 'System' }}</h6>
                                    <span class="fs-2 text-muted text-nowrap ms-2">{{ $log->created_at->diffForHumans(null, true, true) }}</span>
                                </div>
                                <span class="d-block text-muted fs-2 text-truncate" style="max-width: 200px;">{{ $log->description }}</span>
                              </div>
                            </a>
                        @empty
                            <div class="py-5 text-center"><span class="text-muted fs-3">No recent activity.</span></div>
                        @endforelse
                      </div>
                      <div class="py-3 px-4 bg-light rounded-bottom-4 text-center">
                        <a href="{{ route('activity-logs.index') }}" class="text-primary fw-semibold fs-3 text-decoration-none">Check All Activity Logs <i class="ti ti-chevron-right fs-2 ms-1"></i></a>
                      </div>
                    </div>
                </li>

                <!-- PROFIL USER -->
                <div class="dropdown">
                    @php
                        $role = Auth::user()->role;
                        $avatar = ($role === 'Administrator') ? 'user-1.jpg' : 'user-2.jpg';
                    @endphp

                    <a class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle p-1 pe-3 rounded-pill bg-white hover-bg-light transition"
                       href="javascript:void(0)" id="dropProfile" data-bs-toggle="dropdown" aria-expanded="false"
                       style="transition: all 0.3s; border: 1px solid transparent;">

                        <div class="position-relative">
                            <img src="{{ asset('template/assets/images/profile/' . $avatar) }}"
                                 alt="" width="38" height="38"
                                 class="rounded-circle" style="object-fit: cover;">
                            <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                  style="width: 10px; height: 10px; border-width: 2px !important;"></span>
                        </div>

                        <div class="d-none d-md-block text-start lh-sm ms-1">
                            <p class="mb-0 fw-bold text-dark" style="font-size: 13px;">{{ Auth::user()->name }}</p>
                            <small class="text-muted" style="font-size: 11px;">{{ Auth::user()->role }}</small>
                        </div>

                        <i class="ti ti-chevron-down text-muted ms-1" style="font-size: 12px;"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up border-0 shadow-lg mt-3 p-0 rounded-4 overflow-hidden"
                         aria-labelledby="dropProfile" style="min-width: 280px;">

                        <div class="p-4 bg-light-primary bg-opacity-50 text-center border-bottom border-light">
                            <div class="position-relative d-inline-block mb-2">
                                <img src="{{ asset('template/assets/images/profile/' . $avatar) }}"
                                     alt="" width="70" height="70" class="rounded-circle shadow-sm border border-2 border-white">
                                <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                      style="width: 16px; height: 16px;"></span>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark">{{ Auth::user()->name }}</h5>
                            <span class="text-muted fs-3">{{ Auth::user()->email }}</span>
                        </div>

                        <div class="p-2">
                            <a href="{{ route('my-profile') }}" class="d-flex align-items-center gap-3 dropdown-item rounded-3 py-2 px-3 transition hover-bg-light">
                                <div class="bg-light p-2 rounded-circle text-primary"><i class="ti ti-user fs-5"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-semibold text-dark">My Profile</h6>
                                    <span class="text-muted small">Account settings</span>
                                </div>
                            </a>

                            <a href="{{ route('security') }}" class="d-flex align-items-center gap-3 dropdown-item rounded-3 py-2 px-3 mt-1 hover-bg-light transition">
                                <div class="bg-light p-2 rounded-circle text-info"><i class="ti ti-shield-lock fs-5"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-semibold text-dark">Security</h6>
                                    <span class="text-muted small">Change password</span>
                                </div>
                            </a>
                        </div>

                        <div class="p-3 border-top bg-light bg-opacity-25">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-bold d-flex align-items-center justify-content-center gap-2 transition hover-scale">
                                    <i class="ti ti-power fs-5"></i> Sign Out
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