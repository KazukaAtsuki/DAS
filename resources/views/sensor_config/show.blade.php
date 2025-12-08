@extends('layouts.master')

@section('content')
<!-- PADDING TOP 100PX: Agar tidak tertutup header -->
<div class="container-fluid" style="padding-top: 100px;">

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                <!-- HEADER: Judul & Tombol Action -->
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light-primary p-3 rounded-circle text-primary">
                            <i class="ti ti-eye fs-3" style="color: #009688;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Sensor Details</h5>
                            <p class="text-muted small mb-0">Viewing configuration for <span class="fw-bold text-dark">{{ $sensor->sensor_code }}</span></p>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('sensor-config.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">
                            <i class="ti ti-arrow-left me-1"></i> Back
                        </a>
                        <a href="{{ route('sensor-config.edit', $sensor->id) }}" class="btn btn-primary fw-bold rounded-pill px-4" style="background-color: #009688; border: none; box-shadow: 0 4px 12px rgba(0, 150, 136, 0.3);">
                            <i class="ti ti-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">

                    <!-- BAGIAN 1: INFORMASI UTAMA (3 Kolom) -->
                    <div class="row g-4 mb-5">

                        <!-- Identity -->
                        <div class="col-md-4">
                            <h6 class="text-uppercase text-muted fw-bold fs-2 mb-3"><i class="ti ti-id-badge me-1"></i> Identity</h6>

                            <div class="mb-3">
                                <label class="small text-muted d-block">Sensor Code</label>
                                <span class="fw-bolder text-dark fs-4">{{ $sensor->sensor_code }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted d-block">Parameter Name</label>
                                <span class="fw-bolder text-dark fs-4">{{ $sensor->parameter_name }}</span>
                            </div>
                            <div>
                                <label class="small text-muted d-block">Parameter ID</label>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-light-dark text-dark border border-dark rounded-2">{{ $sensor->parameter_id }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Location & Unit -->
                        <div class="col-md-4 border-start border-light ps-md-4">
                            <h6 class="text-uppercase text-muted fw-bold fs-2 mb-3"><i class="ti ti-map-pin me-1"></i> Location & Unit</h6>

                            <div class="mb-3">
                                <label class="small text-muted d-block">Connected Stack</label>
                                <span class="fw-bold text-dark fs-4 d-flex align-items-center gap-2">
                                    <i class="ti ti-building-factory-2 text-primary" style="color: #009688 !important;"></i>
                                    {{ $sensor->stackConfig->stack_name ?? '-' }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <label class="small text-muted d-block">Unit Measurement</label>
                                <span class="fw-bold text-dark fs-4">{{ $sensor->unit->name ?? '-' }}</span>
                            </div>
                            <div>
                                <label class="small text-muted d-block">Status</label>
                                @if($sensor->status == 'Active')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">
                                        <i class="ti ti-circle-check-filled me-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3">
                                        <i class="ti ti-circle-x-filled me-1"></i> Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Connection -->
                        <div class="col-md-4 border-start border-light ps-md-4">
                            <h6 class="text-uppercase text-muted fw-bold fs-2 mb-3"><i class="ti ti-network me-1"></i> Connection</h6>

                            <div class="mb-3">
                                <label class="small text-muted d-block">Analyzer IP</label>
                                <span class="fw-bold text-dark fs-4 font-monospace">{{ $sensor->analyzer_ip ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <label class="small text-muted d-block">Port</label>
                                <span class="fw-bold text-dark fs-4 font-monospace">{{ $sensor->port ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-light my-4">

                    <!-- BAGIAN 2: KONFIGURASI LANJUTAN & FORMULA -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted fw-bold fs-2 mb-3"><i class="ti ti-settings me-1"></i> Advanced Config</h6>

                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-3">
                                <span class="fw-bold text-dark">Extra Parameter</span>
                                @if($sensor->extra_parameter == 'Non Extra Parameter')
                                    <span class="badge bg-secondary">None</span>
                                @elseif($sensor->extra_parameter == 'O2')
                                    <span class="badge bg-success">O2</span>
                                @else
                                    <span class="badge bg-info">Parameter RCA</span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-3">
                                <span class="fw-bold text-dark">O2 Correction</span>
                                @if($sensor->o2_correction == 'O2 Correction')
                                    <span class="badge bg-success"><i class="ti ti-check me-1"></i> Enabled</span>
                                @else
                                    <span class="badge bg-secondary"><i class="ti ti-x me-1"></i> Disabled</span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                                <span class="fw-bold text-dark">Has Reference?</span>
                                @if($sensor->has_parameter_reference == 'Yes')
                                    <span class="badge bg-primary"><i class="ti ti-check me-1"></i> Yes</span>
                                @else
                                    <span class="badge bg-secondary"><i class="ti ti-x me-1"></i> No</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted fw-bold fs-2 mb-3"><i class="ti ti-math-function me-1"></i> Calculation Formula</h6>

                            <div class="p-4 bg-dark rounded-3 position-relative overflow-hidden">
                                <!-- Hiasan visual -->
                                <div class="position-absolute top-0 end-0 opacity-10 p-2">
                                    <i class="ti ti-code fs-1 text-white"></i>
                                </div>

                                <label class="text-white-50 small text-uppercase mb-1">Applied Formula</label>
                                <p class="font-monospace text-white fs-4 mb-0" style="letter-spacing: 1px;">
                                    {{ $sensor->formula ?? 'No formula defined' }}
                                </p>
                            </div>
                            <div class="mt-2 text-muted small fst-italic">
                                * This formula is applied to the raw value before data transmission.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection