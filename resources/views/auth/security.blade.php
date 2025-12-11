@extends('layouts.master')

@push('styles')
<style>
    :root {
        --das-teal: #009688;
        --das-teal-dark: #00796b;
        --das-dark: #1e293b;
    }

    /* Container Adjustment */
    .security-container {
        padding-top: 100px;
        padding-bottom: 50px;
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Card Utama */
    .card-security {
        border: none;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    /* Sisi Kiri (Brand Visual) */
    .brand-sidebar {
        background: linear-gradient(135deg, var(--das-dark) 0%, var(--das-teal) 100%);
        color: white; /* Paksa text putih */
        position: relative;
        overflow: hidden;
    }

    /* Pattern Background Halus */
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

    /* Glass Effect untuk Info Box */
    .glass-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
    }

    /* Form Input Styles */
    .form-control-modern {
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        padding: 12px 15px;
        font-weight: 600;
        color: var(--das-dark);
        transition: all 0.3s;
    }
    .form-control-modern:focus {
        border-color: var(--das-teal);
        box-shadow: 0 0 0 4px rgba(0, 150, 136, 0.1);
    }

    .input-group-text-modern {
        background: #f8fafc;
        border: 2px solid #f1f5f9;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #94a3b8;
    }
    .form-control-with-icon {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }
    .form-control-with-icon:focus {
        border-color: #f1f5f9;
        border-left: none;
    }
    .input-group:focus-within .input-group-text-modern,
    .input-group:focus-within .form-control-with-icon {
        border-color: var(--das-teal);
    }

    .btn-brand {
        background-color: var(--das-teal);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: transform 0.2s;
    }
    .btn-brand:hover {
        background-color: var(--das-teal-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 150, 136, 0.3);
    }
</style>
@endpush

@section('content')
<!-- Logic Penentuan Avatar -->
@php
    $role = Auth::user()->role;
    $avatar = ($role === 'Administrator') ? 'user-1.jpg' : 'user-2.jpg';
@endphp

<div class="container-fluid security-container">

    <!-- Header Sederhana -->
    <div class="mb-4 ps-2">
        <h3 class="fw-bolder text-dark mb-1">Security Settings</h3>
        <p class="text-muted">Manage your password and account protection.</p>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4 rounded-3" role="alert">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white text-success rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                    <i class="ti ti-check fs-6"></i>
                </div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card card-security">
        <div class="row g-0">

            <!-- KOLOM KIRI: VISUAL BRANDING -->
            <div class="col-lg-5 brand-sidebar p-5 d-flex flex-column justify-content-center align-items-center text-center">

                <div class="position-relative z-1">

                    <!-- FOTO PROFIL (Permintaan User) -->
                    <div class="bg-white p-2 rounded-circle mb-4 d-inline-block shadow-lg" style="width: 140px; height: 140px;">
                        <img src="{{ asset('template/assets/images/profile/' . $avatar) }}"
                             alt="Profile"
                             class="rounded-circle w-100 h-100"
                             style="object-fit: cover;">
                    </div>

                    <!-- TEXT WARNA PUTIH (Agar terlihat jelas) -->
                    <h2 class="fw-bold mb-3 text-white">Secure Your Account</h2>
                    <p class="text-white-50 mb-5 fs-4">Ensure your DAS System access remains private and secure.</p>

                    <!-- Tips Box -->
                    <div class="glass-box p-4 text-start">
                        <h6 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">
                            <i class="ti ti-bulb text-warning"></i> Password Requirements:
                        </h6>
                        <ul class="text-white-50 small mb-0 ps-3 gap-2 d-flex flex-column">
                            <li>At least 8 characters long.</li>
                            <li>Combine uppercase & lowercase letters.</li>
                            <li>Include numbers (0-9) & symbols (!@#).</li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: FORM -->
            <div class="col-lg-7 bg-white p-5">
                <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                    <i class="ti ti-key text-primary fs-4" style="color: var(--das-teal) !important;"></i>
                    <h4 class="fw-bold text-dark mb-0">Change Password</h4>
                </div>

                <form action="{{ route('security.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small text-uppercase">Current Password</label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-modern"><i class="ti ti-lock-open"></i></span>
                            <input type="password" name="current_password"
                                   class="form-control form-control-modern form-control-with-icon @error('current_password') is-invalid @enderror"
                                   placeholder="Enter your current password" required>
                        </div>
                        @error('current_password')
                            <small class="text-danger mt-1 fw-bold d-block"><i class="ti ti-alert-circle"></i> {{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- New Password -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-dark small text-uppercase">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-modern"><i class="ti ti-lock"></i></span>
                                <input type="password" name="password"
                                   class="form-control form-control-modern form-control-with-icon @error('password') is-invalid @enderror"
                                   placeholder="Enter new strong password" required>
                            </div>
                            @error('password')
                                <small class="text-danger mt-1 fw-bold d-block"><i class="ti ti-alert-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-dark small text-uppercase">Confirm New Password</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-modern"><i class="ti ti-lock-check"></i></span>
                                <input type="password" name="password_confirmation"
                                   class="form-control form-control-modern form-control-with-icon"
                                   placeholder="Repeat new password" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5">
                        <button type="submit" class="btn btn-brand w-100 py-3 shadow-lg">
                            <i class="ti ti-device-floppy me-2"></i> Update Password
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection