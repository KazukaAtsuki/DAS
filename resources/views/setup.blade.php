<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Initial Setup | DAS System</title>

    <!-- CSS & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --das-teal: #009688;
            --das-teal-light: #e0f2f1;
            --das-bg: #f4f7f6;
            --das-red: #ef4444;
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
            background-size: 30px 30px;
            opacity: 0.12;
            z-index: 0;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            opacity: 0.5;
        }
        .shape-1 { top: -10%; left: -5%; width: 500px; height: 500px; background: var(--das-teal-light); }
        .shape-2 { bottom: -15%; right: -5%; width: 400px; height: 400px; background: #cfd8dc; }

        .floating-icon {
            position: absolute; color: var(--das-teal); opacity: 0.05; z-index: 0;
            animation: float 8s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-30px) rotate(15deg); }
        }

        /* --- SETUP CARD --- */
        .setup-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 28px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            z-index: 10;
            overflow: hidden;
            position: relative;
        }

        .card-top-bar { height: 8px; background: linear-gradient(90deg, var(--das-teal), #4db6ac, var(--das-teal)); width: 100%; }
        .card-inner { padding: 50px 40px; }

        .header-icon {
            width: 80px; height: 80px; background: var(--das-teal-light); color: var(--das-teal);
            border-radius: 22px; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 25px; box-shadow: 0 10px 15px rgba(0, 150, 136, 0.1);
        }

        .main-title { font-size: 1.75rem; font-weight: 800; color: #1e293b; letter-spacing: -0.5px; margin-bottom: 8px; }

        /* --- FORM ELEMENTS --- */
        .form-label { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #64748b; margin-bottom: 8px; }
        .input-group-custom { position: relative; margin-bottom: 5px; }
        .input-group-custom i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.2rem; z-index: 10; transition: color 0.3s; }

        .form-control-das {
            width: 100%; padding: 12px 15px 12px 45px; background: #f8fafc; border: 2px solid #e2e8f0;
            border-radius: 14px; font-weight: 600; color: #1e293b; transition: all 0.3s;
        }

        .form-control-das:focus { outline: none; border-color: var(--das-teal); background: #fff; box-shadow: 0 0 0 4px rgba(0, 150, 136, 0.1); }
        .form-control-das:focus + i { color: var(--das-teal); }

        /* Error State */
        .form-control-das.is-invalid { border-color: var(--das-red) !important; background-color: #fff5f5; }
        .form-control-das.is-invalid + i { color: var(--das-red); }
        .error-feedback { color: var(--das-red); font-size: 0.7rem; font-weight: 700; margin-top: 4px; display: none; text-align: left; padding-left: 5px; }

        /* --- BUTTON --- */
        .btn-das {
            background: linear-gradient(135deg, var(--das-teal) 0%, #00796b 100%);
            color: white; border: none; padding: 16px; border-radius: 14px; width: 100%;
            font-weight: 700; font-size: 1rem; margin-top: 15px; transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(0, 150, 136, 0.2); display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-das:hover { transform: translateY(-2px); box-shadow: 0 15px 25px rgba(0, 150, 136, 0.3); filter: brightness(1.05); }

        .step-badge { display: inline-block; padding: 4px 12px; background: var(--das-teal-light); color: var(--das-teal); border-radius: 50px; font-size: 0.7rem; font-weight: 800; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="bg-pattern"></div>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <i class="ti ti-cpu floating-icon" style="top: 10%; right: 15%; font-size: 100px;"></i>
    <i class="ti ti-database floating-icon" style="bottom: 10%; left: 10%; font-size: 80px; animation-delay: 2s;"></i>

    <div class="setup-card shadow-lg">
        <div class="card-top-bar"></div>
        <div class="card-inner">
            <div class="text-center">
                <div class="step-badge">STEP 01: PROVISIONING</div>
                <div class="header-icon"><i class="ti ti-settings-automation" style="font-size: 40px;"></i></div>
                <h1 class="main-title">Initial System Setup</h1>
                <p class="text-muted small mb-5">Lengkapi identitas unit DAS untuk memulai otorisasi.</p>
            </div>

            <!-- novalidate mematikan tooltip default browser -->
            <form action="{{ route('setup.process') }}" method="POST" id="setupForm" novalidate>
                @csrf

                <div class="mb-4">
                    <label class="form-label">Logger Identifier (ID)</label>
                    <div class="input-group-custom">
                        <input type="text" name="logger_id" id="logger_id" class="form-control-das" placeholder="Contoh: testing-001">
                        <i class="ti ti-barcode"></i>
                        <div class="error-feedback" id="err-logger_id">Logger ID wajib diisi</div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Logger Display Name</label>
                    <div class="input-group-custom">
                        <input type="text" name="logger_name" id="logger_name" class="form-control-das" placeholder="Contoh: Sensor Unit 1">
                        <i class="ti ti-tag"></i>
                        <div class="error-feedback" id="err-logger_name">Nama Unit wajib diisi</div>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">Administrator Email</label>
                    <div class="input-group-custom">
                        <input type="email" name="user_email" id="user_email" class="form-control-das" placeholder="email-admin@company.com">
                        <i class="ti ti-mail"></i>
                        <div class="error-feedback" id="err-user_email">Format email tidak valid atau kosong</div>
                    </div>
                </div>

                <button type="submit" class="btn-das" id="submitBtn">
                    <span>Kirim Permintaan Aktivasi</span>
                    <i class="ti ti-arrow-narrow-right fs-5"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted" style="font-size: 0.7rem; letter-spacing: 2px;">
                    &copy; {{ date('Y') }} PT GenZys Digital Creatindo
                </small>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('setupForm');
        const btn = document.getElementById('submitBtn');

        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Stop form dulu untuk dicek manual

            let isValid = true;
            const fields = [
                { id: 'logger_id', msg: 'Logger ID tidak boleh kosong!' },
                { id: 'logger_name', msg: 'Nama Unit tidak boleh kosong!' },
                { id: 'user_email', msg: 'Email tidak valid atau kosong!', isEmail: true }
            ];

            fields.forEach(field => {
                const el = document.getElementById(field.id);
                const err = document.getElementById('err-' + field.id);
                let value = el.value.trim();

                // Logika Cek Email
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                let check = field.isEmail ? emailPattern.test(value) : value !== "";

                if (!check) {
                    isValid = false;
                    el.classList.add('is-invalid');
                    err.textContent = field.msg;
                    err.style.display = 'block';
                } else {
                    el.classList.remove('is-invalid');
                    err.style.display = 'none';
                }
            });

            if (isValid) {
                // Jika semua oke, baru kirim data dan jalankan loading
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';
                form.submit();
            } else {
                // Notifikasi keren jika gagal
                Swal.fire({
                    icon: 'warning',
                    title: 'Form Belum Lengkap',
                    text: 'Silahkan lengkapi data yang ditandai merah.',
                    confirmButtonColor: '#009688'
                });
            }
        });

        // Hilangkan error saat user mulai mengetik lagi
        document.querySelectorAll('.form-control-das').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errId = 'err-' + this.id;
                document.getElementById(errId).style.display = 'none';
            });
        });
    </script>
</body>
</html>