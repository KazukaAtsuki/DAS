@extends('layouts.master')

@push('styles')
<style>
    :root {
        --das-teal: #009688;
        --das-teal-dark: #00796b;
        --das-dark: #1e293b;
    }

    .profile-container {
        padding-top: 100px;
        padding-bottom: 50px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .card-profile {
        border: none;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .brand-sidebar {
        background: linear-gradient(135deg, var(--das-dark) 0%, var(--das-teal) 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .brand-sidebar::before {
        content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
        background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 30px 30px; opacity: 0.3;
    }

    .glass-box {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px; padding: 20px;
    }

    .form-control-modern {
        border: 2px solid #f1f5f9; border-radius: 12px; padding: 12px 15px;
        font-weight: 600; color: var(--das-dark); transition: all 0.3s;
    }
    .form-control-modern:focus {
        border-color: var(--das-teal); box-shadow: 0 0 0 4px rgba(0, 150, 136, 0.1);
    }
    .form-control-modern[readonly] {
        background-color: #f8fafc; border-color: #e2e8f0; cursor: not-allowed;
    }

    .input-group-text-modern {
        background: #f8fafc; border: 2px solid #f1f5f9; border-right: none;
        border-radius: 12px 0 0 12px; color: #94a3b8;
    }
    .form-control-with-icon { border-left: none; border-radius: 0 12px 12px 0; }
    .form-control-with-icon:focus { border-color: #f1f5f9; border-left: none; }
    .input-group:focus-within .input-group-text-modern,
    .input-group:focus-within .form-control-with-icon { border-color: var(--das-teal); }

    .btn-brand {
        background-color: var(--das-teal); border: none; color: white;
        padding: 12px 30px; border-radius: 12px; font-weight: 700; letter-spacing: 0.5px;
        transition: transform 0.2s;
    }
    .btn-brand:hover {
        background-color: var(--das-teal-dark); color: white; transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 150, 136, 0.3);
    }
</style>
@endpush

@section('content')
@php
    $role = Auth::user()->role;
    $logCount = \App\Models\ActivityLog::where('user_id', Auth::id())->count();
    $joinedDate = Auth::user()->created_at->format('M Y');
    $fullDate = Auth::user()->created_at->format('d F Y');
@endphp

<div class="container-fluid profile-container">

    <div class="mb-4 ps-2">
        <h3 class="fw-bolder text-dark mb-1">My Profile</h3>
        <p class="text-muted">Manage and update your personal information.</p>
    </div>

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

    <div class="card card-profile">
        <div class="row g-0">

            <!-- KOLOM KIRI: VISUAL PROFILE (TANPA FOTO) -->
            <div class="col-lg-5 brand-sidebar p-5 d-flex flex-column justify-content-center align-items-center text-center">
                <div class="position-relative z-1 w-100">

                    <!-- Dekorasi Ikon Besar (Pengganti Foto) -->
                    <div class="mb-4 text-white opacity-50">
                        <i class="ti ti-id-badge-2" style="font-size: 6rem;"></i>
                    </div>

                    <!-- Nama User (Lebih Besar) -->
                    <h1 class="fw-bolder text-white mb-1" style="font-size: 2.5rem;">{{ Auth::user()->name }}</h1>
                    <p class="text-white-50 mb-4 fs-5">{{ Auth::user()->email }}</p>

                    <span class="badge bg-white text-dark px-4 py-2 rounded-pill fw-bold text-uppercase shadow-sm mb-5" style="letter-spacing: 1px;">
                        {{ $role }}
                    </span>

                    <div class="glass-box d-flex justify-content-around align-items-center">
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

            <!-- KOLOM KANAN: FORM EDIT -->
            <div class="col-lg-7 bg-white p-5">

                <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ti ti-edit text-primary fs-4" style="color: var(--das-teal) !important;"></i>
                        <h4 class="fw-bold text-dark mb-0">Edit Account Details</h4>
                    </div>
                </div>

                <form action="{{ route('my-profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted small text-uppercase">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-modern"><i class="ti ti-user"></i></span>
                                <input type="text" name="name" class="form-control form-control-modern form-control-with-icon"
                                       value="{{ Auth::user()->name }}" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-muted small text-uppercase">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-modern"><i class="ti ti-mail"></i></span>
                                <input type="email" name="email" class="form-control form-control-modern form-control-with-icon"
                                       value="{{ Auth::user()->email }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">System Role</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-modern"><i class="ti ti-shield-lock"></i></span>
                                <input type="text" class="form-control form-control-modern form-control-with-icon text-primary fw-bold"
                                       value="{{ Auth::user()->role }}" readonly style="color: var(--das-teal) !important;">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Date Registered</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-modern"><i class="ti ti-calendar"></i></span>
                                <input type="text" class="form-control form-control-modern form-control-with-icon"
                                       value="{{ $fullDate }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="alert bg-light-primary border-0 rounded-3 d-flex align-items-center justify-content-between p-3 mt-4" style="background-color: #e0f2f1;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white p-2 rounded-circle shadow-sm text-primary">
                                <i class="ti ti-key fs-5" style="color: var(--das-teal);"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Change Password?</h6>
                                <small class="text-muted">Security settings are on a separate page.</small>
                            </div>
                        </div>
                        <a href="{{ route('security') }}" class="btn btn-outline-dark btn-sm rounded-pill fw-bold px-3">
                            Go to Security
                        </a>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-brand w-100 py-3 shadow-lg">
                            <i class="ti ti-device-floppy me-2"></i> Update Profile
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
@endsection