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
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

      /* 3. Reset Body Wrapper (Ini biang kerok biasanya) */
      .body-wrapper {
          margin-left: 0 !important; /* Hapus margin bekas sidebar */
          width: 100vw !important;   /* Paksa lebar selebar layar (viewport width) */
          max-width: 100% !important;
          padding: 0 !important;     /* Hapus padding */
      }

      /* 4. Paksa Header Full Width */
      .app-header {
          width: 100% !important;
          max-width: 100% !important;
          margin: 0 !important;
          left: 0 !important;
          right: 0 !important;
          border-radius: 0 !important; /* Hapus lengkungan sudut jika ada */
      }

      /* 5. Konten di bawahnya baru dikasih jarak */
      .content-wrapper {
          padding: 30px;
          margin: 0 auto;
          max-width: 1600px;
      }

      .sidebartoggler { display: none !important; }
  </style>

  @stack('styles')
</head>

<body>
  <!--  Main Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

    <div class="body-wrapper">

      <!-- Panggil Header -->
      @include('partials.header')

      <!-- Konten Utama -->
      <div class="content-wrapper">
        @yield('content')
      </div>

    </div>
  </div>

  <script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/assets/js/app.min.js') }}"></script>
  <script src="{{ asset('template/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('template/assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('template/assets/js/dashboard.js') }}"></script>

  @stack('scripts')
</body>
</html>