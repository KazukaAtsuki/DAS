<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Trusur DAS System</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('template/assets/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('template/assets/css/styles.min.css') }}" />

  <style>
      /* --- CUSTOM STYLES UNTUK LOGIN --- */
      :root {
          --das-teal: #009688;
          --das-teal-dark: #00796b;
          --das-dark: #1A1A1A;
          --das-light-bg: #e0f2f1;
      }

      body {
          background-color: #f8fafc;
          font-family: 'Plus Jakarta Sans', sans-serif;
          overflow-x: hidden;
      }

      /* 1. Background Pattern (Titik-titik halus) */
      .bg-pattern {
          position: fixed;
          top: 0; left: 0; width: 100%; height: 100%;
          background-image: radial-gradient(#009688 1px, transparent 1px);
          background-size: 24px 24px;
          opacity: 0.08;
          z-index: -2;
      }

      /* 2. Shape Dekorasi (Lingkaran Abstrak) */
      .shape-blob {
          position: absolute;
          background: linear-gradient(45deg, var(--das-teal), var(--das-teal-dark));
          border-radius: 50%;
          opacity: 0.15;
          z-index: -1;
          filter: blur(40px);
          animation: float 6s ease-in-out infinite;
      }
      .shape-1 { top: 10%; left: 10%; width: 300px; height: 300px; }
      .shape-2 { bottom: 10%; right: 10%; width: 250px; height: 250px; animation-delay: 2s; background: linear-gradient(45deg, #1A1A1A, #333); }

      @keyframes float {
          0% { transform: translateY(0px); }
          50% { transform: translateY(-20px); }
          100% { transform: translateY(0px); }
      }

      /* 3. Card Login Glassmorphism */
      .card-login {
          border: 1px solid rgba(255, 255, 255, 0.8);
          border-radius: 20px;
          background: rgba(255, 255, 255, 0.95); /* Sedikit transparan */
          backdrop-filter: blur(10px);
          box-shadow: 0 15px 35px rgba(0, 150, 136, 0.1);
          overflow: hidden;
          animation: fadeInUp 0.8s ease-out;
      }

      @keyframes fadeInUp {
          from { opacity: 0; transform: translateY(20px); }
          to { opacity: 1; transform: translateY(0); }
      }

      /* Header Dekorasi di dalam Card */
      .card-header-deco {
          height: 8px;
          background: linear-gradient(90deg, var(--das-teal), var(--das-dark));
          width: 100%;
      }

      /* Input Fields */
      .form-control {
          border: 2px solid #eef2f6;
          border-radius: 10px;
          padding: 12px 15px;
          background-color: #f8fafc;
          font-weight: 500;
      }
      .form-control:focus {
          background-color: #fff;
          border-color: var(--das-teal);
          box-shadow: 0 0 0 4px rgba(0, 150, 136, 0.15);
      }
      .form-floating > label { color: #64748b; }

      /* Tombol */
      .btn-das {
          background: linear-gradient(45deg, var(--das-teal), var(--das-teal-dark));
          border: none;
          color: white;
          font-weight: 700;
          padding: 14px;
          border-radius: 10px;
          letter-spacing: 0.5px;
          box-shadow: 0 4px 15px rgba(0, 150, 136, 0.3);
          transition: all 0.3s ease;
      }
      .btn-das:hover {
          transform: translateY(-2px);
          box-shadow: 0 8px 20px rgba(0, 150, 136, 0.4);
      }

      /* Footer Text */
      .login-footer {
          font-size: 0.85rem;
          color: #94a3b8;
          margin-top: 2rem;
          border-top: 1px solid #f1f5f9;
          padding-top: 1.5rem;
      }
  </style>
</head>

<body>

  <!-- Background Elements -->
  <div class="bg-pattern"></div>
  <div class="shape-blob shape-1"></div>
  <div class="shape-blob shape-2"></div>

  <!-- Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-5 col-xxl-3">

            <div class="card mb-0 card-login">
              <!-- Garis Hiasan Atas -->
              <div class="card-header-deco"></div>

              <div class="card-body p-5">

                <!-- LOGO SECTION -->
                <div class="text-center mb-5 login-header">
                    <a href="javascript:void(0)" class="text-nowrap logo-img d-inline-flex align-items-center gap-3 text-decoration-none">
                        <!-- Icon SVG -->
                        <div class="p-2 bg-light rounded-3 shadow-sm">
                            <svg width="40" height="40" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="50" height="50" rx="12" fill="#E0F2F1"/>
                                <path d="M10 25H16L20 15L30 35L34 25H40" stroke="#009688" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="20" cy="15" r="3" fill="#1A1A1A"/>
                                <circle cx="30" cy="35" r="3" fill="#1A1A1A"/>
                            </svg>
                        </div>

                        <div style="line-height: 1.1; text-align: left;">
                            <h3 class="m-0 fw-bolder text-dark" style="letter-spacing: -0.5px;">DAS</h3>
                            <span class="d-block fw-bold" style="color: #009688; font-size: 11px; letter-spacing: 2px;">DAS SYSTEM</span>
                        </div>
                    </a>
                    <p class="text-muted mt-3 mb-0 fs-3">Welcome back! Please access your account.</p>
                </div>

                <!-- Alert Error -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center" role="alert">
                        <i class="ti ti-alert-circle fs-5 me-2"></i>
                        <ul class="mb-0 ps-3 small m-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Alert Success -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                        <small>{{ session('success') }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form Login -->
                <form action="{{ route('login.process') }}" method="POST">
                  @csrf

                  <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                    <label for="email"><i class="ti ti-mail me-2"></i>Email Address</label>
                  </div>

                  <div class="form-floating mb-4">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                    <label for="password"><i class="ti ti-lock me-2"></i>Password</label>
                  </div>

                  <!-- Remember Me & Forgot Password (Opsional/Visual Saja) -->
                  <div class="d-flex justify-content-between mb-4">
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                          <label class="form-check-label text-dark small" for="flexCheckChecked">
                              Remember Device
                          </label>
                      </div>
                      {{-- <a class="text-primary fw-bold small text-decoration-none" href="javascript:void(0)">Forgot Password?</a> --}}
                  </div>

                  <button type="submit" class="btn btn-das w-100 mb-4">
                    Sign In <i class="ti ti-arrow-right ms-2"></i>
                  </button>

                  <div class="text-center login-footer">
                    <p class="mb-0 fw-bold">© {{ date('Y') }} PT. Trusur Unggul Teknusa</p>
                    <small class="text-muted">Data Acquisition System v1.0</small>
                  </div>

                </form>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>