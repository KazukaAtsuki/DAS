<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Otorisasi Sistem | DAS</title>

    <!-- CSS & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --das-teal: #009688;
            --das-teal-light: #e0f2f1;
            --das-bg: #f4f7f6;
        }

        body {
            background-color: var(--das-bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* --- DEKORASI BACKGROUND --- */
        .bg-pattern {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(var(--das-teal) 0.8px, transparent 0.8px);
            background-size: 24px 24px;
            opacity: 0.15;
            z-index: 0;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.4;
        }
        .shape-1 { top: -10%; right: -5%; width: 400px; height: 400px; background: var(--das-teal-light); }
        .shape-2 { bottom: -10%; left: -5%; width: 350px; height: 350px; background: #cfd8dc; }

        .floating-icon {
            position: absolute;
            color: var(--das-teal);
            opacity: 0.05;
            z-index: 0;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        /* --- CARD STYLING --- */
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 24px;
            padding: 0;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
            text-align: center;
            z-index: 10;
            overflow: hidden;
            position: relative;
        }

        .card-top-bar {
            height: 6px;
            background: linear-gradient(90deg, var(--das-teal), #4db6ac);
            width: 100%;
        }

        .card-inner {
            padding: 45px 40px;
        }

        .icon-box {
            width: 80px;
            height: 80px;
            background: white;
            color: var(--das-teal);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(0, 150, 136, 0.15);
            border: 1px solid var(--das-teal-light);
        }

        .main-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .logger-info {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            padding: 6px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 30px;
        }

        /* --- INPUT FIELD --- */
        .verif-label {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-bottom: 15px;
            display: block;
        }

        .verif-field {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            color: var(--das-teal);
            font-size: 2.2rem;
            font-weight: 800;
            text-align: center;
            width: 100%;
            padding: 12px;
            letter-spacing: 12px;
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }

        .verif-field:focus {
            border-color: var(--das-teal);
            box-shadow: 0 0 0 5px rgba(0, 150, 136, 0.12);
            transform: translateY(-2px);
        }

        /* --- BUTTONS --- */
        .btn-authorize {
            background: linear-gradient(135deg, var(--das-teal) 0%, #00796b 100%);
            border: none;
            color: white;
            font-weight: 700;
            padding: 16px;
            border-radius: 16px;
            width: 100%;
            margin-top: 25px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 8px 15px rgba(0, 150, 136, 0.25);
        }

        .btn-authorize:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(0, 150, 136, 0.35);
            filter: brightness(1.05);
        }

        /* Tombol Kembali ke Setup */
        .btn-setup {
            background: transparent;
            border: 2px solid #e2e8f0;
            color: #64748b;
            font-weight: 700;
            padding: 12px;
            border-radius: 16px;
            width: 100%;
            margin-top: 12px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .btn-setup:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: var(--das-teal);
        }

        .footer-text {
            margin-top: 35px;
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 600;
        }

        .alert-error {
            background: #fff5f5;
            border-left: 4px solid #f87171;
            color: #991b1b;
            border-radius: 12px;
            font-size: 0.85rem;
            text-align: left;
            padding: 12px 16px;
        }
    </style>
</head>
<body>

    <div class="bg-pattern"></div>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <i class="ti ti-shield-check floating-icon" style="top: 15%; left: 10%; font-size: 80px;"></i>
    <i class="ti ti-lock floating-icon" style="bottom: 15%; right: 10%; font-size: 60px; animation-delay: 1s;"></i>
    <i class="ti ti-key floating-icon" style="top: 60%; left: 15%; font-size: 40px; animation-delay: 2s;"></i>

    <div class="auth-card">
        <div class="card-top-bar"></div>

        <div class="card-inner">
            <div class="icon-box">
                <i class="ti ti-shield-lock" style="font-size: 38px;"></i>
            </div>

            <h1 class="main-title">Otorisasi Sistem</h1>

            <div class="logger-info">
                <i class="ti ti-broadcast text-teal"></i>
                ID: {{ session('setup_logger_id', 'UNKNOWN') }}
            </div>

            <p class="text-muted mb-4 small" style="line-height: 1.6;">
                Aksi ini memerlukan verifikasi keamanan. Silahkan masukkan kode yang dikirimkan oleh Admin Pusat.
            </p>

            @if(session('error') || $errors->any())
                <div class="alert alert-error mb-4 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-alert-circle-filled fs-5 me-2"></i>
                        <span>{{ session('error') ?? 'Kode verifikasi salah atau expired.' }}</span>
                    </div>
                </div>
            @endif

            <form action="{{ route('verify.submit') }}" method="POST" id="authForm">
                @csrf

                <span class="verif-label">Enter 6-Digit Code</span>
                <input type="text" name="verif_code" id="codeField"
                       class="verif-field" maxlength="6"
                       placeholder="000000" autocomplete="off" required
                       inputmode="numeric">

                <button type="submit" class="btn-authorize" id="submitBtn">
                    <i class="ti ti-lock-open fs-5"></i>
                    <span>Verifikasi Sekarang</span>
                </button>

                <!-- TOMBOL MENU SETUP (BARU) -->
                <a href="{{ route('setup.index') }}" class="btn-setup">
                    <i class="ti ti-settings"></i>
                    <span>Kembali ke Pengaturan ID</span>
                </a>
            </form>

            <div class="footer-text">
                <i class="ti ti-copyright me-1"></i> {{ date('Y') }} PT Trusur Unggul Teknusa
            </div>
        </div>
    </div>

    <script>
        const codeField = document.getElementById('codeField');
        const submitBtn = document.getElementById('submitBtn');

        codeField.focus();

        codeField.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        document.getElementById('authForm').addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';
        });
    </script>
</body>
</html>