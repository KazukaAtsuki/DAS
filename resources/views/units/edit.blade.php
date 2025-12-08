@extends('layouts.master')

@section('content')
<div class="container-fluid" style="padding-top: 100px;">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card border-0 shadow-sm rounded-4">

                <!-- CARD HEADER -->
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <!-- Ikon Edit (Warning/Kuning) -->
                        <div class="bg-light-warning p-2 rounded-circle text-warning">
                            <i class="ti ti-pencil fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Edit Unit</h5>
                            <p class="text-muted small mb-0">Update measurement unit details.</p>
                        </div>
                    </div>
                    <a href="{{ route('units.index') }}" class="btn btn-light text-muted btn-sm rounded-pill px-3">
                        <i class="ti ti-x fs-5"></i>
                    </a>
                </div>

                <!-- CARD BODY -->
                <div class="card-body p-4">
                    <form action="{{ route('units.update', $unit->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase">Unit Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-ruler"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 bg-light ps-0" value="{{ $unit->name }}" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('units.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4" style="background-color: #009688; border: none; box-shadow: 0 4px 12px rgba(0, 150, 136, 0.3);">
                                <i class="ti ti-check me-1"></i> Update Unit
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection