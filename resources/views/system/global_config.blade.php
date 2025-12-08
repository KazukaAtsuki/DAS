@extends('layouts.master')

@push('styles')
<style>
    /* Custom Style untuk efek modern */
    .card-modern {
        transition: all 0.3s ease;
        border: none;
    }
    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
    }
    .border-left-primary { border-left: 5px solid #5d87ff !important; }
    .border-left-info { border-left: 5px solid #49beff !important; }
    .border-left-warning { border-left: 5px solid #ffae1f !important; }

    .status-widget {
        background: linear-gradient(145deg, #1e293b, #0f172a);
        color: white;
    }
    .status-indicator {
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- Header Page -->
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div>
            <h3 class="fw-bolder text-dark mb-1">Global Configuration</h3>
            <span class="text-muted fs-3">Manage system identity, server connections, and security protocols.</span>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fs-3 fw-semibold">
                <i class="ti ti-server me-1"></i> System V1.0
            </span>
        </div>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4 rounded-3" role="alert">
            <div class="bg-success text-white rounded-circle p-1 d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px;">
                <i class="ti ti-check fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0">Success!</h6>
                <div class="fs-3">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('global-config.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <!-- KOLOM KIRI: SETTINGS FORM -->
            <div class="col-lg-8">

                <!-- CARD 1: GENERAL IDENTITY -->
                <div class="card card-modern shadow-sm rounded-4 mb-4 border-left-primary">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-light-primary text-primary p-3 rounded-3 me-3">
                                <i class="ti ti-fingerprint fs-6"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">General Identity</h5>
                                <small class="text-muted">Define the unique identity for this unit.</small>
                            </div>
                        </div>

                        <div class="form-floating mb-2">
                            <input type="text" class="form-control fw-bold text-dark" id="dasName" name="das_unit_name"
                                   value="{{ $config->das_unit_name }}" placeholder="DAS Name" required>
                            <label for="dasName"><i class="ti ti-device-desktop me-1"></i> DAS Unit Name <span class="text-danger">*</span></label>
                        </div>
                        <div class="form-text ps-1"><i class="ti ti-info-circle me-1"></i>Visible on all generated reports and logs.</div>
                    </div>
                </div>

                <!-- CARD 2: CONNECTION -->
                <div class="card card-modern shadow-sm rounded-4 mb-4 border-left-info">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-light-info text-info p-3 rounded-3 me-3">
                                <i class="ti ti-broadcast fs-6"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Server Connection</h5>
                                <small class="text-muted">Configure destination server endpoint.</small>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold text-dark" id="serverHost" name="server_host"
                                           value="{{ $config->server_host }}" placeholder="127.0.0.1" required>
                                    <label for="serverHost"><i class="ti ti-world me-1"></i> Server Host / IP <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control fw-bold text-dark" id="apiEndpoint" name="api_endpoint"
                                           value="{{ $config->api_endpoint }}" placeholder="/api" required>
                                    <label for="apiEndpoint"><i class="ti ti-link me-1"></i> API Endpoint <span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: SECURITY -->
                <div class="card card-modern shadow-sm rounded-4 mb-4 border-left-warning">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-light-warning text-warning p-3 rounded-3 me-3">
                                <i class="ti ti-shield-lock fs-6"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Security & Auth</h5>
                                <small class="text-muted">Manage access keys for data transmission.</small>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control fw-bold text-dark" id="apiKey" name="server_api_key"
                                   placeholder="Key">
                            <label for="apiKey"><i class="ti ti-key me-1"></i> Server API Key</label>
                        </div>
                        <div class="form-text ps-1"><i class="ti ti-lock me-1"></i>Leave blank to keep the current active key.</div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: STATUS & ACTIONS -->
            <div class="col-lg-4">

                <!-- WIDGET: RCA STATUS (DARK MODE) -->
                <div class="card status-widget shadow rounded-4 mb-4 text-center overflow-hidden border-0">
                    <div class="card-body p-5">
                        <h6 class="text-white-50 text-uppercase letter-spacing-2 mb-4 fs-2 fw-bold">RCA Mode Status</h6>

                        <div class="d-inline-flex align-items-center justify-content-center bg-dark bg-opacity-50 rounded-circle p-4 mb-3 status-indicator border border-secondary">
                            <i class="ti ti-power fs-8 text-secondary"></i>
                        </div>

                        <h3 class="fw-bolder text-white mt-2">NORMAL MODE</h3>
                        <p class="text-white-50 fs-3 mb-0 px-2 mt-3">
                            <i class="ti ti-activity me-1"></i> System running in standard measurement cycle.
                        </p>
                    </div>
                    <div class="card-footer bg-black bg-opacity-25 border-top border-secondary py-3">
                        <span class="badge bg-success bg-opacity-75 text-white">System Online</span>
                    </div>
                </div>

                <!-- WIDGET: SAVE ACTION -->
                <div class="card bg-white border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="p-2 bg-light-primary rounded-circle me-2 text-primary">
                                <i class="ti ti-device-floppy fs-5"></i>
                            </div>
                            <h5 class="fw-bold mb-0">Save Changes?</h5>
                        </div>
                        <p class="text-muted small mb-4">
                            Please verify all parameters before saving. Updates will reflect on the next data transmission cycle.
                        </p>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center">
                            <i class="ti ti-check me-2"></i> Update Configuration
                        </button>
                    </div>
                </div>

                <!-- Last Update Info -->
                <div class="text-center mt-4">
                    <span class="text-muted fs-2">
                        <i class="ti ti-clock me-1"></i> Last update: {{ now()->format('d M Y, H:i') }}
                    </span>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection