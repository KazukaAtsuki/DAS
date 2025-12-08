@extends('layouts.master')

@section('content')
<div class="container-fluid" style="padding-top: 100px;">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light-warning p-2 rounded-circle text-warning">
                            <i class="ti ti-pencil fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Edit User</h5>
                            <p class="text-muted small mb-0">Update user information.</p>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}" class="btn btn-light text-muted btn-sm rounded-pill px-3">
                        <i class="ti ti-x fs-5"></i>
                    </a>
                </div>

                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ti ti-user"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 bg-light ps-0" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Role <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ti ti-shield-lock"></i></span>
                                    <select name="role" class="form-select border-start-0 bg-light ps-0">
                                        <option value="Administrator" {{ $user->role == 'Administrator' ? 'selected' : '' }}>Administrator</option>
                                        <option value="Operator" {{ $user->role == 'Operator' ? 'selected' : '' }}>Operator</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small text-uppercase">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-mail"></i></span>
                                <input type="email" name="email" class="form-control border-start-0 bg-light ps-0" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-lock"></i></span>
                                <input type="password" name="password" class="form-control border-start-0 bg-light ps-0" placeholder="Leave empty to keep current password">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4" style="background-color: #009688; border: none;">
                                <i class="ti ti-check me-1"></i> Update User
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection