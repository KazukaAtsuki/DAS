@extends('layouts.master')

@section('content')
<div class="container-fluid" style="padding-top: 100px;">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light-info p-3 rounded-circle text-info">
                            <i class="ti ti-eye fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">User Detail</h5>
                            <p class="text-muted small mb-0">Viewing user information.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary fw-bold rounded-pill px-4" style="background-color: #009688; border: none;">
                            <i class="ti ti-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">

                    <div class="d-flex align-items-center mb-5">
                        <div class="me-4">
                            <img src="{{ asset('template/assets/images/profile/user-1.jpg') }}" alt="" width="80" height="80" class="rounded-circle shadow-sm border border-2 border-white">
                        </div>
                        <div>
                            <h4 class="fw-bolder text-dark mb-1">{{ $user->name }}</h4>
                            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill border border-primary">{{ $user->role }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold mb-1">Email Address</label>
                            <div class="p-3 bg-light rounded-3 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="ti ti-mail text-primary"></i>
                                {{ $user->email }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold mb-1">Joined Date</label>
                            <div class="p-3 bg-light rounded-3 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="ti ti-calendar text-primary"></i>
                                {{ $user->created_at->format('d F Y, H:i') }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection