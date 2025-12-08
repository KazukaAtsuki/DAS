<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="text-nowrap logo-img">
          <img src="{{ asset('template/assets/images/logos/dark-logo.svg') }}" width="180" alt="" />
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
          <i class="ti ti-x fs-8"></i>
        </div>
      </div>

      <!-- Sidebar navigation-->
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">

          <!-- ============================= -->
          <!-- GROUP: HOME                   -->
          <!-- ============================= -->
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Home</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ url('/') }}" aria-expanded="false">
              <span>
                <i class="ti ti-layout-dashboard"></i>
              </span>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>

          <!-- ============================= -->
          <!-- GROUP: MASTER DATA            -->
          <!-- ============================= -->
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">MASTER DATA</span>
          </li>

          <!-- Menu: Stack Config -->
          <!-- Note: Ini sudah pakai route() karena controllernya sudah kita buat -->
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('stack-config.index') }}" aria-expanded="false">
              <span>
                <i class="ti ti-server"></i> <!-- Icon Server/Stack -->
              </span>
              <span class="hide-menu">Stack Config</span>
            </a>
          </li>

          <!-- Menu: Sensors Config -->
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('sensor-config.index') }}" aria-expanded="false">
              <span>
                <i class="ti ti-broadcast"></i>
              </span>
              <span class="hide-menu">Sensors Config</span>
            </a>
          </li>

          <!-- Menu: References -->
          <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('references.index') }}" aria-expanded="false">
              <span>
                <i class="ti ti-book-2"></i>
              </span>
              <span class="hide-menu">References</span>
            </a>
          </li>

          <!-- Menu: Units -->
          <li class="sidebar-item">
           <a class="sidebar-link" href="{{ route('units.index') }}" aria-expanded="false">
           <span>
        <i class="ti ti-scale"></i>
           </span>
         <span class="hide-menu">Units</span>
      </a>
    </li>

          <!-- ============================= -->
          <!-- GROUP: SYSTEM                 -->
          <!-- ============================= -->
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">SYSTEM</span>
          </li>

          <!-- Menu: Global Config -->
<li class="sidebar-item">
    <a class="sidebar-link" href="{{ route('global-config.index') }}" aria-expanded="false">
      <span>
        <i class="ti ti-settings"></i>
      </span>
      <span class="hide-menu">Global Config</span>
    </a>
  </li>

          <!-- Menu: User Management -->
<li class="sidebar-item">
    <a class="sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
      <span>
        <i class="ti ti-users"></i>
      </span>
      <span class="hide-menu">User Management</span>
    </a>
  </li>

        </ul>

        {{-- <!-- Bagian Upgrade to Pro (Opsional, bisa dihapus kalau tidak perlu) -->
        <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
            <div class="d-flex">
              <div class="unlimited-access-title me-3">
                <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Laravel PKL</h6>
                <button class="btn btn-primary fs-2 fw-semibold lh-sm">Project V1</button>
              </div>
              <div class="unlimited-access-img">
                <img src="{{ asset('template/assets/images/backgrounds/rocket.png') }}" alt="" class="img-fluid">
              </div>
            </div>
          </div> --}}

      </nav>
      <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>