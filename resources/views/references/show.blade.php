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
                            <h5 class="fw-bold text-dark mb-0">Reference Detail</h5>
                            <p class="text-muted small mb-0">Viewing details.</p>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('references.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </a>
                        <a href="{{ route('references.edit', $reference->id) }}" class="btn btn-primary fw-bold rounded-pill px-4" style="background-color: #009688; border: none;">
                            <i class="ti ti-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold mb-1">Stack Name</label>
                            <div class="p-3 bg-light rounded-3 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="ti ti-building-factory-2 text-primary"></i>
                                {{ $reference->sensorConfig->stackConfig->stack_name ?? '-' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold mb-1">Parameter (Sensor)</label>
                            <div class="p-3 bg-light rounded-3 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="ti ti-broadcast text-primary"></i>
                                {{ $reference->sensorConfig->parameter_name ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold mb-1">Range Start</label>
                            <div class="p-3 bg-light rounded-3 fw-bold text-dark font-monospace">
                                {{ $reference->range_start }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted text-uppercase fw-bold mb-1">Range End</label>
                            <div class="p-3 bg-light rounded-3 fw-bold text-dark font-monospace">
                                {{ $reference->range_end }}
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="small text-muted text-uppercase fw-bold mb-1">Formula</label>
                        <div class="p-4 bg-dark rounded-3 position-relative overflow-hidden">
                            <div class="position-absolute top-0 end-0 opacity-10 p-2">
                                <i class="ti ti-math-function fs-1 text-white"></i>
                            </div>
                            <p class="font-monospace text-white fs-4 mb-0">{{ $reference->formula }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection