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
                            <h5 class="fw-bold text-dark mb-0">Edit Reference</h5>
                            <p class="text-muted small mb-0">Update reference data.</p>
                        </div>
                    </div>
                    <a href="{{ route('references.index') }}" class="btn btn-light text-muted btn-sm rounded-pill px-3">
                        <i class="ti ti-x fs-5"></i>
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('references.update', $reference->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase">Parameter (Sensor) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-broadcast"></i></span>
                                <select name="sensor_config_id" class="form-select border-start-0 bg-light ps-0" required>
                                    @foreach($sensors as $s)
                                        <option value="{{ $s->id }}" {{ $reference->sensor_config_id == $s->id ? 'selected' : '' }}>
                                            {{ $s->stackConfig->stack_name ?? 'No Stack' }} - {{ $s->parameter_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Range Start <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ti ti-arrow-bar-to-right"></i></span>
                                    <input type="number" step="0.01" name="range_start" class="form-control border-start-0 bg-light ps-0" value="{{ $reference->range_start }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small text-uppercase">Range End <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ti ti-arrow-bar-to-left"></i></span>
                                    <input type="number" step="0.01" name="range_end" class="form-control border-start-0 bg-light ps-0" value="{{ $reference->range_end }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase">Formula <span class="text-danger">*</span></label>
                            <textarea name="formula" class="form-control bg-light font-monospace" rows="3" required>{{ $reference->formula }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('references.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4" style="background-color: #009688; border: none;">
                                <i class="ti ti-check me-1"></i> Update Reference
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection