<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplikasi Laravel PKL</title>

  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="shortcut icon" type="image/png" href="{{ asset('template/assets/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('template/assets/css/styles.min.css') }}" />

  <style>
      /* 1. Reset Margin Body Browser */
      body {
          margin: 0;
          padding: 0;
          overflow-x: hidden;
          background-color: #f4f6f9;
          font-family: 'Plus Jakarta Sans', sans-serif;
      }

      /* 2. Reset Wrapper Utama Template */
      #main-wrapper {
          width: 100% !important;
          padding: 0 !important;
          margin: 0 !important;
      }

      /* 3. Reset Body Wrapper */
      .body-wrapper {
          margin-left: 0 !important;
          width: 100vw !important;
          max-width: 100% !important;
          padding: 0 !important;
      }

      /* 4. Paksa Header Full Width */
      .app-header {
          width: 100% !important;
          max-width: 100% !important;
          margin: 0 !important;
          left: 0 !important;
          right: 0 !important;
          border-radius: 0 !important;
      }

      /* 5. Konten di bawahnya baru dikasih jarak */
      .content-wrapper {
          padding: 30px;
          padding-bottom: 80px !important;
          margin: 0 auto;
          max-width: 1600px;
      }

      .sidebartoggler { display: none !important; }

      /* Animasi Putar untuk Ikon Loading */
      .spin-anim {
          animation: rotate 1s linear infinite;
          display: inline-block;
      }
      @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

      /* --- FOOTER BRANDING DAS --- */
      .das-footer {
          background-color: #ffffff;
          border-top: 1px solid #e2e8f0;
          padding: 12px 0;
          position: fixed;
          bottom: 0;
          left: 0;
          width: 100%;
          z-index: 999;
          box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.03);
      }

      .footer-text {
          font-size: 0.75rem;
          color: #64748b;
          font-weight: 500;
          letter-spacing: 0.5px;
          margin: 0;
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 10px;
      }

      .brand-name {
          color: #009688;
          font-weight: 800;
          text-decoration: none;
          transition: 0.3s;
      }

      .brand-name:hover {
          color: #00796b;
          text-decoration: underline;
      }

      .footer-dot {
          width: 4px;
          height: 4px;
          background-color: #cbd5e1;
          border-radius: 50%;
      }
  </style>

  @stack('styles')
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="body-wrapper">
      @include('partials.header')
      <div class="content-wrapper">
        @yield('content')
      </div>

      <footer class="das-footer">
          <div class="container-fluid">
              <p class="footer-text">
                  <span class="fw-bold">&copy; {{ date('Y') }} DAS SYSTEM V4.0</span>
                  <span class="footer-dot"></span>
                  <span>Aplikasi dikembangkan oleh
                      <a href="javascript:void(0)" class="brand-name">PT GenZys Digital Creatindo</a>
                  </span>
                  <span class="footer-dot"></span>
                  <span class="badge" style="background-color: #e0f2f1; color: #009688; font-size: 10px; padding: 4px 10px;">
                      <i class="ti ti-bolt me-1"></i> High Performance Engine
                  </span>
              </p>
          </div>
      </footer>
    </div>
  </div>

  <!-- JAVASCRIPT LIBRARIES -->
  <script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/assets/js/app.min.js') }}"></script>
  <script src="{{ asset('template/assets/libs/simplebar/dist/simplebar.js') }}"></script>

  <!-- ========================================== -->
  <!-- FIX ERROR APEXCHARTS: Hanya load di halaman Dashboard -->
  <!-- ========================================== -->
  @if(request()->routeIs('dashboard'))
    <script src="{{ asset('template/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/dashboard.js') }}"></script>
  @endif

  <!-- ========================================== -->
  <!-- LOGIKA COUNTDOWN TIMER FIXED -->
  <!-- ========================================== -->
 <!-- LOGIKA COUNTDOWN TIMER FIXED -->
  @if(isset($remainingSeconds) && $remainingSeconds > 0)
    <script>
        $(document).ready(function() {
            // Ambil sisa detik dari variabel global yang di-share AppServiceProvider
            let timeLeft = parseInt("{{ $remainingSeconds }}");

            function updateCountdown() {
                const timerElement = $("#countdownText");
                if (!timerElement.length) return;

                if (timeLeft <= 0) {
                    timerElement.text("00:00");
                    clearInterval(timerInterval);
                    // Refresh halaman otomatis saat waktu habis
                    setTimeout(() => { window.location.reload(); }, 1000);
                    return;
                }

                // Hitung Menit dan Detik
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;

                // Tampilkan format 00:00
                let mDisplay = minutes < 10 ? "0" + minutes : minutes;
                let sDisplay = seconds < 10 ? "0" + seconds : seconds;

                timerElement.text(mDisplay + ":" + sDisplay);

                timeLeft--;
            }

            const timerInterval = setInterval(updateCountdown, 1000);
            updateCountdown();
        });
    </script>
  @endif

  <!-- SWEETALERT NOTIFICATION HANDLER -->
  <script>
      $(document).ready(function() {
          @if(session('swal_success'))
              Swal.fire({ icon: 'success', title: 'SYSTEM AUTHORIZED', text: "{{ session('swal_success') }}", toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true, background: '#f0fdfa', iconColor: '#009688' });
          @endif
          @if(session('success'))
              Swal.fire({ icon: 'success', title: 'Success', text: "{{ session('success') }}", confirmButtonColor: '#009688' });
          @endif
          @if(session('error'))
              Swal.fire({ icon: 'error', title: 'Oops...', text: "{{ session('error') }}", confirmButtonColor: '#d33' });
          @endif
      });
  </script>

  @stack('scripts')
</body>
</html>