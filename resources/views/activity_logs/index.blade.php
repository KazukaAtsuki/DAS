@extends('layouts.master')

@push('styles')
<style>
    /* 1. Background Bersih (Professional Look) */
    body {
        background-color: #f4f6f9; /* Abu-abu sangat muda */
        min-height: 100vh;
    }

    /* Container Adjustment */
    .logs-container {
        padding-top: 110px;
        padding-bottom: 80px;
        max-width: 900px;
        margin: 0 auto;
    }

    /* 2. Timeline Logic */
    .timeline {
        position: relative;
        padding-left: 20px;
    }

    /* Garis Lurus (Solid) Abu-abu */
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 24px;
        width: 3px;
        background-color: #e2e8f0; /* Garis abu-abu tegas */
        border-radius: 2px;
        z-index: 0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        z-index: 1;
    }

    /* 3. Card Style (Simple & Clean) */
    .timeline-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); /* Soft Shadow */
        transition: all 0.3s ease;
        margin-left: 35px;
        position: relative;
    }

    .timeline-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        border-color: #cbd5e1;
    }

    /* Panah Kecil di Kiri Card */
    .timeline-card::after {
        content: '';
        position: absolute;
        top: 25px;
        left: -8px;
        width: 16px;
        height: 16px;
        background: #ffffff;
        border-left: 1px solid #edf2f7;
        border-bottom: 1px solid #edf2f7;
        transform: rotate(45deg);
    }

    /* Ikon Bulat di Garis */
    .timeline-icon {
        position: absolute;
        left: -21px; /* Posisi pas di tengah garis */
        top: 20px;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        border: 4px solid #f4f6f9; /* Border tebal warna background agar terlihat terpisah dari garis */
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        z-index: 2;
        font-size: 1.1rem;
    }

    /* Typography */
    .header-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1e293b;
    }

    .badge-modern {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 5px 10px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    /* Pagination Clean */
    .page-link {
        border: 1px solid #e2e8f0;
        margin: 0 3px;
        border-radius: 8px;
        color: #64748b;
        background: white;
    }
    .page-item.active .page-link {
        background: #009688;
        color: white;
        border-color: #009688;
    }
</style>
@endpush

@section('content')
<div class="container-fluid logs-container">

    <!-- Header Section (Clean) -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h2 class="header-title fw-bolder mb-1">Activity Timeline</h2>
            <p class="text-muted mb-0">Audit trail of system events.</p>
        </div>
        <button class="btn btn-white border bg-white text-dark fw-bold rounded-pill px-4 py-2 shadow-sm" onclick="window.location.reload()">
            <i class="ti ti-refresh me-2"></i> Refresh
        </button>
    </div>

    <!-- Timeline Wrapper -->
    <div class="timeline">

        @forelse($logs as $log)
            <div class="timeline-item">

                <!-- LOGIC WARNA & ICON -->
                @php
                    $color = 'primary';
                    $icon = 'ti-activity';
                    // Warna background badge yang soft
                    $bg_soft = 'bg-light-primary';

                    if($log->action == 'LOGIN') { $color = 'info'; $icon = 'ti-login'; $bg_soft = 'bg-light-info'; }
                    elseif($log->action == 'LOGOUT') { $color = 'warning'; $icon = 'ti-logout'; $bg_soft = 'bg-light-warning'; }
                    elseif($log->action == 'CREATE') { $color = 'success'; $icon = 'ti-plus'; $bg_soft = 'bg-light-success'; }
                    elseif($log->action == 'DELETE') { $color = 'danger'; $icon = 'ti-trash'; $bg_soft = 'bg-light-danger'; }
                    elseif($log->action == 'UPDATE') { $color = 'primary'; $icon = 'ti-edit'; $bg_soft = 'bg-light-primary'; }

                    // Logic Avatar
                    $userRole = $log->user->role ?? 'Unknown';
                    $userAvatar = ($userRole === 'Administrator') ? 'user-1.jpg' : 'user-2.jpg';
                @endphp

                <!-- Icon Bulat di Garis -->
                <div class="timeline-icon text-{{ $color }}">
                    <i class="ti {{ $icon }}"></i>
                </div>

                <!-- Kartu Log (Clean White) -->
                <div class="timeline-card border-start-{{ $color }} border-start-4">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <!-- User Info -->
                        <div class="d-flex align-items-center gap-3">
                            <!-- Avatar Kecil -->
                            <img src="{{ asset('template/assets/images/profile/' . $userAvatar) }}"
                                 width="32" height="32" class="rounded-circle border" alt="user">

                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $log->user->name ?? 'System' }}</h6>
                                <small class="text-muted" style="font-size: 11px;">
                                    {{ $log->created_at->format('d M Y') }} • {{ $log->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>

                        <!-- Badge Action Minimalis -->
                        <span class="badge badge-modern {{ $bg_soft }} text-{{ $color }}">
                            {{ $log->action }}
                        </span>
                    </div>

                    <hr class="my-2 border-light">

                    <!-- Isi Pesan -->
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="mb-0 text-secondary fw-medium" style="font-size: 0.95rem;">
                            {{ $log->description }}
                        </p>

                        <!-- Waktu Relatif -->
                        <small class="text-muted fst-italic" style="font-size: 0.75rem;">
                            <i class="ti ti-clock me-1"></i> {{ $log->created_at->diffForHumans() }}
                        </small>
                    </div>

                </div>
            </div>
        @empty
            <!-- Empty State Sederhana -->
            <div class="text-center py-5">
                <div class="bg-white p-4 rounded-circle shadow-sm d-inline-block mb-3 border">
                    <i class="ti ti-files fs-1 text-muted"></i>
                </div>
                <h5 class="fw-bold text-dark mt-2">No activities found</h5>
                <p class="text-muted">System logs are currently empty.</p>
            </div>
        @endforelse

    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5 mb-5">
        {{ $logs->links() }}
    </div>

</div>
@endsection