@extends('layouts.master')

@section('content')
<div class="container-fluid" style="padding-top: 100px;"> <!-- FIX PADDING TOP -->

    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- Tampilkan Alert jika ada error dari sistem -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-alert-triangle fs-5 me-2"></i>
                        <div>
                            <strong>Error!</strong><br>
                            {{ session('error') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2 rounded-circle" style="background-color: #e0f2f1;">
                            <i class="ti ti-scale fs-3" style="color: #009688;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Create New Unit</h5>
                            <p class="text-muted small mb-0">Add a new measurement unit to the system.</p>
                        </div>
                    </div>
                    <a href="{{ route('units.index') }}" class="btn btn-light text-muted btn-sm rounded-pill px-3">
                        <i class="ti ti-x fs-5"></i>
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('units.store') }}" method="POST" id="formUnit">
                        @csrf

                        <!-- Field Input Nama Unit -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase">Unit Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-ruler"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 bg-light ps-0"
                                       placeholder="e.g. mg/m3" value="{{ old('name') }}" required autofocus>
                            </div>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tombol Action -->
                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('units.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm"
                                    style="background-color: #009688; border: none;" id="btnSubmit">
                                <i class="ti ti-check me-1"></i> Save Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Efek loading saat tombol diklik
    document.getElementById('formUnit').addEventListener('submit', function() {
        let btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<i class="ti ti-loader-2 spin-anim"></i> Saving...';
    });
</script>

<style>
    .spin-anim {
        animation: rotate 1s linear infinite;
        display: inline-block;
    }
    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endsection