@extends('layouts.master')

@section('content')
<div class="container-fluid" style="padding-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
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

                                <!-- HIGH LIMIT (BAKU MUTU) -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-danger small">High Limit (Baku Mutu)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 border-danger text-danger"><i class="ti ti-alert-triangle"></i></span>
                                        <input type="number" step="0.01" name="limit_value" class="form-control border-start-0 bg-light ps-0 border-danger" placeholder="e.g. 50.00">
                                    </div>
                                </div>

                                <!-- WARNING 1 -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-warning small">Warning 1</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 border-warning text-warning"><i class="ti ti-alert-circle"></i></span>
                                        <input type="number" step="0.01" name="warning_1" class="form-control border-start-0 bg-light ps-0 border-warning" placeholder="e.g. 40.00">
                                    </div>
                                </div>

                                <!-- WARNING 2 -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-warning small">Warning 2</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 border-warning text-warning"><i class="ti ti-alert-circle"></i></span>
                                        <input type="number" step="0.01" name="warning_2" class="form-control border-start-0 bg-light ps-0 border-warning" placeholder="e.g. 45.00">
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
                            </div>

                            <!-- KOLOM KANAN (TETAP SAMA) -->
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-muted fw-bold fs-2 mb-3"><i class="ti ti-settings me-1"></i> Configuration</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Parameter Name <span class="text-danger">*</span></label>
                                    <input type="text" name="parameter_name" class="form-control bg-light" placeholder="e.g. Opacity / SO2" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Connected Stack <span class="text-danger">*</span></label>
                                    <select name="stack_config_id" class="form-select bg-light" required>
                                        <option value="" selected disabled>Select Stack</option>
                                        @foreach($stacks as $s)
                                            <option value="{{ $s->id }}">{{ $s->stack_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Status</label>
                                    <select name="status" class="form-select bg-light">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Port Connection</label>
                                    <input type="number" name="port" class="form-control bg-light" placeholder="e.g. 502">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small">Has Parameter Reference? <span class="text-danger">*</span></label>
                                    <select name="has_parameter_reference" class="form-select bg-light" required>
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6 class="text-uppercase text-muted fw-bold fs-2 mb-2"><i class="ti ti-math-function me-1"></i> Calculation</h6>
                            <textarea name="formula" class="form-control bg-light" rows="3" placeholder="e.g. (x * 1.5) + 2" style="font-family: monospace;"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('sensor-config.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4" style="background-color: #009688; border: none;">
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