@extends('layouts.master')

@section('content')
<!-- PADDING TOP 100PX: Agar tidak tertutup header fixed -->
<div class="container-fluid" style="padding-top: 100px;">

    <div class="row justify-content-center">
        <div class="col-lg-10"> <!-- Menggunakan col-lg-10 agar form lebih lebar dan lega -->

            <div class="card border-0 shadow-sm rounded-4">

                <!-- HEADER CARD -->
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light-primary p-2 rounded-circle text-primary">
                            <i class="ti ti-broadcast fs-3" style="color: #009688;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Create New Sensor</h5>
                            <p class="text-muted small mb-0">Configure sensor parameters and connection details.</p>
                        </div>
                    </div>
                    <a href="{{ route('sensor-config.index') }}" class="btn btn-light text-muted btn-sm rounded-pill px-3">
                        <i class="ti ti-x fs-5"></i> Close
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('sensor-config.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">

                            <!-- KOLOM KIRI -->
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted fw-bold fs-2 mb-3"><i class="ti ti-info-circle me-1"></i> Basic Info</h6>

                                <!-- Sensor Code -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Sensor Code <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-barcode"></i></span>
                                        <input type="text" name="sensor_code" class="form-control border-start-0 bg-light ps-0" placeholder="e.g. SENS-001" required>
                                    </div>
                                </div>

                                <!-- Parameter ID -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Parameter ID <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-id"></i></span>
                                        <input type="text" name="parameter_id" class="form-control border-start-0 bg-light ps-0" placeholder="e.g. PARAM-01" required>
                                    </div>
                                    <div class="form-text text-info small"><i class="ti ti-alert-circle me-1"></i> Must be synchronized with DIS System.</div>
                                </div>

                                <!-- Unit -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Unit Measurement <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-scale"></i></span>
                                        <select name="unit_id" class="form-select border-start-0 bg-light ps-0" required>
                                            <option value="" selected disabled>Select Unit</option>
                                            @foreach($units as $u)
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Analyzer IP -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Analyzer IP Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-network"></i></span>
                                        <input type="text" name="analyzer_ip" class="form-control border-start-0 bg-light ps-0" placeholder="e.g. 192.168.1.10">
                                    </div>
                                </div>

                                <!-- Extra Parameter -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Extra Parameter <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-list-details"></i></span>
                                        <select name="extra_parameter" class="form-select border-start-0 bg-light ps-0" required>
                                            <option value="Non Extra Parameter">No Extra Parameter</option>
                                            <option value="O2">O2</option>
                                            <option value="Parameter RCA">Parameter RCA</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- O2 Correction -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">O2 Correction <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-adjustments"></i></span>
                                        <select name="o2_correction" class="form-select border-start-0 bg-light ps-0" required>
                                            <option value="Non Correction">Non Correction</option>
                                            <option value="O2 Correction">O2 Correction</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- KOLOM KANAN -->
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted fw-bold fs-2 mb-3"><i class="ti ti-settings me-1"></i> Configuration</h6>

                                <!-- Parameter Name -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Parameter Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-tag"></i></span>
                                        <input type="text" name="parameter_name" class="form-control border-start-0 bg-light ps-0" placeholder="e.g. Opacity / SO2" required>
                                    </div>
                                </div>

                                <!-- Stack -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Connected Stack <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-building-factory-2"></i></span>
                                        <select name="stack_config_id" class="form-select border-start-0 bg-light ps-0" required>
                                            <option value="" selected disabled>Select Stack</option>
                                            @foreach($stacks as $s)
                                                <option value="{{ $s->id }}">{{ $s->stack_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Status</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-activity"></i></span>
                                        <select name="status" class="form-select border-start-0 bg-light ps-0">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Port -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Port Connection</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-plug"></i></span>
                                        <input type="number" name="port" class="form-control border-start-0 bg-light ps-0" placeholder="e.g. 502">
                                    </div>
                                </div>

                                <!-- Has Ref -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Has Parameter Reference? <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-link"></i></span>
                                        <select name="has_parameter_reference" class="form-select border-start-0 bg-light ps-0" required>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FORMULA SECTION (Full Width) -->
                        <div class="mt-4">
                            <h6 class="text-uppercase text-muted fw-bold fs-2 mb-2"><i class="ti ti-math-function me-1"></i> Calculation</h6>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small">Formula <span class="text-danger">*</span></label>
                                <textarea name="formula" class="form-control bg-light" rows="3" placeholder="e.g. (x * 1.5) + 2" style="font-family: monospace;"></textarea>
                                <div class="form-text text-muted">Use standard mathematical notation. Variable 'x' represents raw value.</div>
                            </div>
                        </div>

                        <!-- BUTTONS -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('sensor-config.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4" style="background-color: #009688; border: none; box-shadow: 0 4px 12px rgba(0, 150, 136, 0.3);">
                                <i class="ti ti-check me-1"></i> Save Sensor
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection