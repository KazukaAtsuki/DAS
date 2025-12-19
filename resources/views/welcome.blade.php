<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trusur DAS - Advanced Monitoring System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --das-teal: #009688;
            --das-teal-dark: #00796b;
            --das-dark: #1e293b;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* NAVBAR */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .btn-teal {
            background-color: var(--das-teal);
            color: white;
            border-radius: 50px;
            padding: 10px 28px;
            font-weight: 700;
            transition: all 0.3s;
            border: none;
        }
        .btn-teal:hover {
            background-color: var(--das-teal-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 150, 136, 0.3);
            color: white;
        }

        /* HERO SECTION */
        .hero-section {
            padding: 120px 0 80px;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        /* Dekorasi Background */
        .hero-bg-circle {
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(0,150,136,0.15) 0%, transparent 70%);
            border-radius: 50%;
            top: -20%; right: -10%;
            z-index: 0;
            animation: pulse 5s infinite ease-in-out;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .hero-content { position: relative; z-index: 1; }
        .hero-title { font-size: 3.5rem; font-weight: 800; line-height: 1.2; margin-bottom: 20px; }
        .hero-subtitle { font-size: 1.2rem; color: #94a3b8; margin-bottom: 40px; }

        /* FITUR CARDS */
        .features-section { padding: 80px 0; }
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: var(--das-teal);
        }
        .icon-box {
            width: 70px; height: 70px;
            background-color: #e0f2f1;
            color: var(--das-teal);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            margin-bottom: 25px;
        }

        /* FOOTER */
        footer { background: #0f172a; color: #64748b; padding: 30px 0; text-align: center; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <!-- Logo SVG Kecil -->
                <svg width="32" height="32" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="50" height="50" rx="12" fill="#E0F2F1"/>
                    <path d="M10 25H16L20 15L30 35L34 25H40" stroke="#009688" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <h5 class="m-0 fw-bold text-dark" style="line-height: 1;">TRUSUR</h5>
                    <small class="fw-bold" style="color: #009688; font-size: 10px; letter-spacing: 1px;">DAS SYSTEM</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">

                    <!-- LOGIC TOMBOL LOGIN/DASHBOARD -->
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="btn btn-dark rounded-pill px-4 fw-bold">Go to Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="btn btn-teal">Login System</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section d-flex align-items-center">
        <div class="hero-bg-circle"></div>
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">
                        <i class="ti ti-rocket text-primary"></i> System Version 3.0
                    </span>
                    <h1 class="hero-title">
                        Real-time Industrial <br>
                        <span style="color: var(--das-teal);">Emission Monitoring</span>
                    </h1>
                    <p class="hero-subtitle">
                        Ensure compliance and operational efficiency with our advanced Data Acquisition System. Monitor sensors, generate reports, and audit data seamlessly.
                    </p>
                    <div class="d-flex gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-teal btn-lg px-5">Open Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-teal btn-lg px-5">Get Started</a>
                        @endauth
                        <a href="#features" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center">
                    <!-- Ilustrasi Dashboard Modern -->
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/data-analysis-illustration-download-in-svg-png-gif-file-formats--analytics-business-chart-graph-marketing-pack-illustrations-3686705.png"
                         alt="Dashboard Hero" class="img-fluid" style="filter: drop-shadow(0 20px 40px rgba(0,0,0,0.4)); transform: perspective(1000px) rotateY(-10deg);">
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-uppercase fw-bold" style="color: var(--das-teal); letter-spacing: 2px;">Key Capabilities</h6>
                <h2 class="fw-bolder display-6 text-dark">Why Choose Trusur DAS?</h2>
            </div>

            <div class="row g-4">
                <!-- Fitur 1 -->
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="ti ti-activity-heartbeat"></i></div>
                        <h4>Real-time Monitoring</h4>
                        <p class="text-muted">Live sensor data streaming with instant updates every second. Visualized with interactive charts.</p>
                    </div>
                </div>
                <!-- Fitur 2 -->
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="ti ti-clipboard-check"></i></div>
                        <h4>RCA Audit Mode</h4>
                        <p class="text-muted">Dedicated Relative Correlation Audit mode to separate calibration data from daily operational logs.</p>
                    </div>
                </div>
                <!-- Fitur 3 -->
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box"><i class="ti ti-file-analytics"></i></div>
                        <h4>Automated Reporting</h4>
                        <p class="text-muted">Auto-generated Hourly Averages and easy export to Excel & SIMPEL format for compliance.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <p class="mb-0">&copy; 2025 <strong class="text-white">PT. Trusur Unggul Teknusa</strong>. All Rights Reserved.</p>
            <small>Data Acquisition System v3.0</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>