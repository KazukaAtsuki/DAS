<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        :root {
            --das-teal: #009688;
            --das-dark: #1e293b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .error-card {
            text-align: center;
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            color: var(--das-teal);
            line-height: 1;
            text-shadow: 4px 4px 0px rgba(0, 150, 136, 0.1);
            position: relative;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--das-dark);
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .error-desc {
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 40px;
        }

        .btn-home {
            background-color: var(--das-teal);
            color: white;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 10px 20px rgba(0, 150, 136, 0.2);
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-home:hover {
            background-color: #00796b;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 150, 136, 0.3);
            color: white;
        }

        /* Animasi Melayang */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* Dekorasi Background */
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            z-index: -1;
            opacity: 0.5;
        }
        .c1 { width: 300px; height: 300px; background: #e0f2f1; top: -100px; left: -100px; }
        .c2 { width: 200px; height: 200px; background: #b2dfdb; bottom: -50px; right: -50px; }

    </style>
</head>

<body>

    <div class="bg-circle c1"></div>
    <div class="bg-circle c2"></div>

    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="error-card">

                <!-- Ilustrasi Angka -->
                <div class="error-code">
                    404
                </div>

                <h1 class="error-title">Signal Lost / Page Not Found</h1>

                <p class="error-desc">
                    The page you are looking for might have been removed,
                    had its name changed, or is temporarily unavailable.
                </p>

                <div class="d-flex justify-content-center gap-3">
                    <!-- Tombol Kembali (Cek Login dulu) -->
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-home">
                            <i class="ti ti-home"></i> Back to Dashboard
                        </a>
                    @else
                        <a href="{{ url('/') }}" class="btn-home">
                            <i class="ti ti-home"></i> Back to Home
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </div>

</body>
</html>