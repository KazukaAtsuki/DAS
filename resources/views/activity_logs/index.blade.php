@extends('layouts.master')

@push('styles')
<style>
    /* 1. Animated Gradient Background */
    body {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        min-height: 100vh;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Container Adjustment */
    .logs-container {
        padding-top: 120px;
        padding-bottom: 80px;
        max-width: 900px;
        margin: 0 auto;
    }

    /* 2. Glassmorphism Card Style */
    .timeline-card {
        background: rgba(255, 255, 255, 0.85); /* Semi-transparent white */
        backdrop-filter: blur(10px); /* Blur effect */
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        transition: all 0.3s ease;
        margin-left: 30px;
        position: relative;
    }

    .timeline-card:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        background: rgba(255, 255, 255, 0.95);
        border-color: #fff;
    }

    /* Arrow Pointer (Segitiga kecil di kiri card) */
    .timeline-card::after {
        content: '';
        position: absolute;
        top: 20px;
        left: -10px;
        width: 0;
        height: 0;
        border-top: 10px solid transparent;
        border-bottom: 10px solid transparent;
        border-right: 10px solid rgba(255, 255, 255, 0.85);
    }

    /* 3. Timeline Line & Icon */
    .timeline {
        position: relative;
        padding-left: 20px;
    }
    /* Garis Putus-putus */
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 24px;
        width: 2px;
        background-image: linear-gradient(to bottom, rgba(255,255,255,0.5) 50%, rgba(255,255,255,0) 0%);
        background-position: right;
        background-size: 2px 10px;
        background-repeat: repeat-y;
        z-index: 0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2.5rem;
        z-index: 1;
        opacity: 0;
        animation: fadeIn 0.5s ease-out forwards;
    }

    /* Animasi muncul satu per satu */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Stagger Animation Delay */
    .timeline-item:nth-child(1) { animation-delay: 0.1s; }
    .timeline-item:nth-child(2) { animation-delay: 0.2s; }
    .timeline-item:nth-child(3) { animation-delay: 0.3s; }
    .timeline-item:nth-child(4) { animation-delay: 0.4s; }
    .timeline-item:nth-child(5) { animation-delay: 0.5s; }

    .timeline-icon {
        position: absolute;
        left: -26px;
        top: 10px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 4px solid rgba(255,255,255,0.5);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        z-index: 2;
        font-size: 1.2rem;
    }

    /* Typography */
    .header-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        letter-spacing: -1px;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .text-glass {
        color: rgba(255, 255, 255, 0.9);
    }

    .badge-modern {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 6px 12px;
        border-radius: 8px;
        text-transform: uppercase;
    }

    /* Override Pagination */
    .page-link {
        border: none;
        margin: 0 5px;
        border-radius: 10px;
        color: #1e293b;
        background: rgba(255, 255, 255, 0.8);
        font-weight: bold;
    }
    .page-item.active .page-link {
        background: #1e293b;
        color: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')
<div class="container-fluid logs-container">

    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="header-title fw-bolder mb-1">Activity Timeline</h1>
            <p class="text-glass mb-0 fs-4">Real-time audit trail of system events.</p>
        </div>
        <button class="btn btn-white text-dark fw-bold rounded-pill px-4 py-2 shadow-lg hover-scale" onclick="window.location.reload()">
            <i class="ti ti-refresh me-2"></i> Refresh Data
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
                    $bg_soft = 'bg-light-primary';

                    if($log->action == 'LOGIN') { $color = 'info'; $icon = 'ti-login'; $bg_soft = 'bg-light-info'; }
                    elseif($log->action == 'LOGOUT') { $color = 'warning'; $icon = 'ti-logout'; $bg_soft = 'bg-light-warning'; }
                    elseif($log->action == 'CREATE') { $color = 'success'; $icon = 'ti-plus'; $bg_soft = 'bg-light-success'; }
                    elseif($log->action == 'DELETE') { $color = 'danger'; $icon = 'ti-trash'; $bg_soft = 'bg-light-danger'; }
                    elseif($log->action == 'UPDATE') { $color = 'primary'; $icon = 'ti-edit'; $bg_soft = 'bg-light-primary'; }
                @endphp

                <!-- Icon Bulat di Kiri -->
                <div class="timeline-icon text-{{ $color }}">
                    <i class="ti {{ $icon }}"></i>
                </div>

                <!-- Kartu Log -->
                <div class="timeline-card border-start-{{ $color }} border-start-5">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- User Info -->
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white p-2 rounded-circle shadow-sm">
                                <img src="{{ asset('template/assets/images/profile/user-1.jpg') }}" width="35" class="rounded-circle" alt="user">
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">{{ $log->user->name ?? 'System' }}</h5>
                                <small class="text-muted">{{ $log->created_at->format('d M Y') }} • {{ $log->created_at->format('H:i') }}</small>
                            </div>
                        </div>

                        <!-- Badge Action -->
                        <span class="badge badge-modern {{ $bg_soft }} text-{{ $color }}">
                            {{ $log->action }}
                        </span>
                    </div>

                    <!-- Isi Pesan -->
                    <div class="p-3 rounded-3 bg-light bg-opacity-50 border border-light">
                        <p class="mb-0 text-dark fw-medium fs-4" style="line-height: 1.6;">
                            {{ $log->description }}
                        </p>
                    </div>

                    <!-- Waktu Relatif -->
                    <div class="mt-3 text-end">
                        <small class="text-muted fw-bold fst-italic">
                            <i class="ti ti-clock me-1"></i> {{ $log->created_at->diffForHumans() }}
                        </small>
                    </div>

                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="bg-white bg-opacity-25 p-5 rounded-circle d-inline-block mb-3 backdrop-blur shadow-lg">
                    <i class="ti ti-ghost fs-1 text-white"></i>
                </div>
                <h3 class="fw-bold text-white mt-3">No activities found.</h3>
                <p class="text-white-50">It seems quiet here...</p>
            </div>
        @endforelse

    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5 mb-5">
        {{ $logs->links() }}
    </div>

</div>
@endsection