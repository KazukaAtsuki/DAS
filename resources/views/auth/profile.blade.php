@extends('layouts.master')

@push('styles')
<style>
    :root {
        --das-teal: #009688;
        --das-teal-dark: #00796b;
        --das-dark: #1e293b;
    }

    /* Container Adjustment */
    .profile-container {
        padding-top: 100px;
        padding-bottom: 50px;
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Card Utama */
    .card-profile {
        border: none;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    /* Sisi Kiri (Brand Visual) */
    .brand-sidebar {
        background: linear-gradient(135deg, var(--das-dark) 0%, var(--das-teal) 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    /* Pattern Background */
    .brand-sidebar::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 30px 30px;
        opacity: 0.3;
    }

    /* Glass Box untuk Stats */
    .glass-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 20px;
    }

    /* Readonly Input Styles */
    .form-control-modern {
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        padding: 12px 15px;
        font-weight: 600;
        color: var(--das-dark);
        background-color: #f8fafc; /* Abu-abu tipis tanda readonly */
        cursor: not-allowed; /* Kursor tanda dilarang */
    }

    .input-group-text-modern {
        background: #f1f5f9;
        border: 2px solid #f1f5f9;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #94a3b8;
    }

    .form-control-with-icon {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    /* Efek saat hover di input readonly */
    .input-group:hover .input-group-text-modern i {
        color: var(--das-teal);
    }

    .badge-role {
        background-color: rgba(0, 150, 136, 0.1);
        color: var(--das-teal);
        border: 1px solid rgba(0, 150, 136, 0.2);
    }
</style>
@endpush

@section('content')
<!-- Logic Data -->
@php
    $role = Auth::user()->role;
    $avatar = ($role === 'Administrator') ? 'user-1.jpg' : 'user-2.jpg';
    $logCount = \App\Models\ActivityLog::where('user_id', Auth::id())->count();
    $joinedDate = Auth::user()->created_at->format('M Y');
    $fullDate = Auth::user()->created_at->format('d F Y');
@endphp

<div class="container-fluid profile-container">

    <!-- Header Sederhana -->
    <div class="mb-4 ps-2">
        <h3 class="fw-bolder text-dark mb-1">My Profile</h3>
        <p class="text-muted">View your personal account information.</p>
    </div>

    <div class="card card-profile">
        <div class="row g-0">

            <!-- KOLOM KIRI: VISUAL PROFILE (TEAL GRADIENT) -->
            <div class="col-lg-5 brand-sidebar p-5 d-flex flex-column justify-content-center align-items-center text-center">

                <div class="position-relative z-1 w-100">

                    <!-- FOTO PROFIL BESAR -->
                    <div class="position-relative d-inline-block mb-4">
                        <div class="p-2 bg-white bg-opacity-25 rounded-circle backdrop-blur">
                            <img src="{{ asset('template/assets/images/profile/' . $avatar) }}"
                                 alt="Profile"
                                 class="rounded-circle shadow-lg border border-2 border-white"
                                 style="width: 140px; height: 140px; object-fit: cover;">
                        </div>
                        <!-- Status Online Badge -->
                        <span class="position-absolute bottom-0 end-0 bg-success border border-4 border-white rounded-circle"
                              style="width: 25px; height: 25px; margin-bottom: 10px; margin-right: 10px;"
                              title="Active"></span>
                    </div>

                    <h2 class="fw-bold text-white mb-1">{{ Auth::user()->name }}</h2>
                    <p class="text-white-50 mb-3">{{ Auth::user()->email }}</p>

                    <!-- Role Badge -->
                    <span class="badge bg-white text-dark px-4 py-2 rounded-pill fw-bold text-uppercase shadow-sm mb-4">
                        {{ $role }}
                    </span>

                    <!-- STATISTIK (Glass Box) -->
                    <div class="glass-box d-flex justify-content-around align-items-center mt-4">
                        <div class="text-center">
                            <h3 class="fw-bold text-white mb-0">{{ $logCount }}</h3>
                            <small class="text-white-50 text-uppercase" style="font-size: 10px; letter-spacing: 1px;">Total Logs</small>
                        </div>
                        <div class="vr bg-white opacity-25"></div>
                        <div class="text-center">
                            <h3 class="fw-bold text-white mb-0">{{ $joinedDate }}</h3>
                            <small class="text-white-50 text-uppercase" style="font-size: 10px; letter-spacing: 1px;">Member Since</small>
                        </div>
                    </div>

                </div>
            </div>

            <!-- KOLOM KANAN: READ ONLY INFO -->
            <div class="col-lg-7 bg-white p-5">

                <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-id-badge-2 text-primary fs-4" style="color: var(--das-teal) !important;"></i>
                        <h4 class="fw-bold text-dark mb-0">Account Details</h4>
                    </div>
                    <!-- Indikator View Only -->
                    <span class="badge bg-light text-muted border px-3"><i class="ti ti-lock me-1"></i> View Only</span>
                </div>

                <div class="row g-4">

                    <!-- Full Name (Locked) -->
                    <div class="col-12">
                        <label class="form-label fw-bold text-muted small text-uppercase">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-modern"><i class="ti ti-user"></i></span>
                            <input type="text" class="form-control form-control-modern form-control-with-icon"
                                   value="{{ Auth::user()->name }}" readonly>
                        </div>
                    </div>

                    <!-- Email Address (Locked) -->
                    <div class="col-12">
                        <label class="form-label fw-bold text-muted small text-uppercase">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-modern"><i class="ti ti-mail"></i></span>
                            <input type="text" class="form-control form-control-modern form-control-with-icon"
                                   value="{{ Auth::user()->email }}" readonly>
                        </div>
                    </div>

                    <!-- System Role (Locked) -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">System Role</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-modern"><i class="ti ti-shield-lock"></i></span>
                            <input type="text" class="form-control form-control-modern form-control-with-icon text-primary fw-bold"
                                   value="{{ Auth::user()->role }}" readonly style="color: var(--das-teal) !important;">
                        </div>
                    </div>

                    <!-- Account Created (Locked) -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small text-uppercase">Date Registered</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-modern"><i class="ti ti-calendar"></i></span>
                            <input type="text" class="form-control form-control-modern form-control-with-icon"
                                   value="{{ $fullDate }}" readonly>
                        </div>
                    </div>

                </div>

                <!-- Info Box Bawah -->
                <div class="alert bg-light-primary border-0 rounded-3 d-flex align-items-center justify-content-between p-3 mt-5" style="background-color: #e0f2f1;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white p-2 rounded-circle shadow-sm text-primary">
                            <i class="ti ti-settings fs-5" style="color: var(--das-teal);"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">Need to update info?</h6>
                            <small class="text-muted">To change password, go to Security Settings.</small>
                        </div>
                    </div>
                    <!-- Tombol Navigasi ke Security -->
                    <a href="{{ route('security') }}" class="btn btn-dark rounded-pill fw-bold px-4 shadow-sm" style="background-color: var(--das-dark); border: none;">
                        Open Security
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection