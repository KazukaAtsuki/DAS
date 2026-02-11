@extends('layouts.master')

@section('content')
<div class="container-fluid" style="padding-top: 100px;"> <!-- Menyesuaikan padding agar tidak tertutup header -->

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Edit Stack Configuration</h5>
                        <p class="text-muted small mb-0">Update the details for the existing emission stack.</p>
                    </div>
                    <a href="{{ route('stack-config.index') }}" class="btn btn-light text-muted btn-sm rounded-pill">
                        <i class="ti ti-x fs-5"></i>
                    </a>
                </div>

                <div class="card-body p-4">
                    <!-- Form Update menggunakan PUT method -->
                    <form action="{{ route('stack-config.update', $stack->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Stack Name -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase">Stack Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-building-factory-2"></i></span>
                                <input type="text" name="stack_name" class="form-control border-start-0 bg-light ps-0"
                                       value="{{ $stack->stack_name }}" placeholder="e.g. Stack LK 01" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Oxygen Reference -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-dark small text-uppercase">Oxygen Reference (%)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ti ti-percentage"></i></span>
                                    <input type="number" step="0.01" name="oxygen_reference" class="form-control border-start-0 bg-light ps-0"
                                           value="{{ $stack->oxygen_reference }}" placeholder="e.g. 7.0">
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-dark small text-uppercase">Status</label>
                                <select name="status" class="form-select bg-light">
                                    <option value="Active" {{ $stack->status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ $stack->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-2 pt-3 border-top">
                            <a href="{{ route('stack-config.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4" style="background-color: #009688; border: none;">
                                <i class="ti ti-refresh me-1"></i> Update Stack
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection