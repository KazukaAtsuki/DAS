@extends('layouts.master')

@push('styles')
<style>
    :root {
        --das-teal: #009688;
        --das-dark: #1e293b;
        --das-gray: #64748b;
        --das-bg-soft: #f1f5f9;

        /* Warna Khusus RCA */
        --rca-bg-dark: #450a0a; /* Merah Marun Gelap */
        --rca-accent: #ef4444;  /* Merah Terang */
    }

    /* Container Adjustment */
    .dashboard-container {
        padding-top: 100px;
        padding-bottom: 50px;
        max-width: 1600px;
        margin: 0 auto;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); /* Animasi Halus */
    }

    /* --- STYLE KHUSUS MODE RCA (MODERN DARK RED) --- */
    .dashboard-container.mode-rca-active {
        background: linear-gradient(135deg, #450a0a 0%, #1a0505 100%);
        border-radius: 24px;
        padding: 40px;
        margin-top: 80px;
        box-shadow: 0 20px 50px rgba(239, 68, 68, 0.2);
    }

    /* Header & Teks saat RCA */
    .mode-rca-active .dashboard-title {
        color: #ffffff !important;
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }
    .mode-rca-active .text-muted {
        color: rgba(255,255,255,0.6) !important;
    }

    /* Card saat RCA (Glass Dark) */
    .mode-rca-active .card-sensor {
        background: rgba(255, 255, 255, 0.05); /* Transparan */
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 68, 68, 0.3);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }

    .mode-rca-active .card-sensor:hover {
        border-color: var(--rca-accent);
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.4);
        transform: scale(1.02);
    }

    .mode-rca-active .card-sensor::after {
        background: var(--rca-accent);
        box-shadow: 0 0 10px var(--rca-accent);
    }

    /* Teks dalam Card saat RCA */
    .mode-rca-active .param-name { color: #fca5a5; } /* Merah muda */
    .mode-rca-active .value-display {
        color: #ffffff;
        text-shadow: 0 0 10px rgba(255,255,255,0.5);
    }
    .mode-rca-active .unit-display {
        background: rgba(239, 68, 68, 0.2);
        color: #fff;
    }
    .mode-rca-active .raw-container {
        background: rgba(0,0,0,0.3);
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    .mode-rca-active .raw-value { color: #fff; }

    /* --- STYLE DEFAULT (NORMAL MODE) --- */
    .card-sensor {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .card-sensor:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -10px rgba(0, 150, 136, 0.15);
        border-color: rgba(0, 150, 136, 0.3);
    }
    .card-sensor::after {
        content: '';
        position: absolute;
        top: 0; left: 20px; right: 20px; height: 3px;
        background: var(--das-teal);
        border-radius: 0 0 4px 4px;
        transition: all 0.3s;
    }

    /* Typography */
    .param-name { color: var(--das-gray); font-size: 0.85rem; letter-spacing: 1.2px; font-weight: 700; }
    .value-display { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--das-dark); letter-spacing: -2px; line-height: 1; font-size: 3.5rem; }
    .unit-display { font-size: 1rem; font-weight: 600; color: var(--das-gray); background: #f1f5f9; padding: 2px 8px; border-radius: 6px; }

    .raw-container { background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
    .raw-label { font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; display: flex; align-items: center; gap: 5px; }
    .raw-value { font-family: 'Courier New', monospace; font-weight: 700; color: var(--das-dark); font-size: 0.9rem; }

    /* Live Indicator */
    .live-badge { background-color: rgba(34, 197, 94, 0.1); color: #16a34a; font-size: 0.7rem; font-weight: 800; padding: 4px 10px; border-radius: 30px; display: flex; align-items: center; gap: 6px; letter-spacing: 0.5px; }
    .live-badge.offline { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .live-dot { width: 6px; height: 6px; background-color: #22c55e; border-radius: 50%; box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); animation: pulse-green 1.5s infinite; }
    .offline .live-dot { background-color: #ef4444; animation: none; box-shadow: none; }
    @keyframes pulse-green { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(34, 197, 94, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); } }
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="dashboard-wrapper" class="container-fluid dashboard-container {{ $isRcaOn ? 'mode-rca-active' : '' }}">

    <!-- HEADER & CONTROLS -->
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-5 gap-3">

        <!-- Title -->
        <div>
            <h2 class="fw-bolder text-dark mb-1 dashboard-title" style="letter-spacing: -0.5px; transition: color 0.3s;">
                {{ $isRcaOn ? 'AUDIT MODE ACTIVE' : 'Monitoring Dashboard' }}
            </h2>
            <div class="d-flex align-items-center gap-2 text-muted small">
                <span class="badge bg-white text-secondary border shadow-sm">
                    <i class="ti ti-server me-1"></i> System V3
                </span>
                <i class="ti ti-chevron-right" style="font-size: 10px;"></i>
                @php
                    $currentStackName = $selectedStackId ? ($stacks->firstWhere('id', $selectedStackId)->stack_name ?? 'Unknown') : 'All Stacks Overview';
                @endphp
                <span class="badge bg-primary text-white shadow-sm px-3">{{ $currentStackName }}</span>
            </div>
        </div>

        <!-- Controls -->
        <div class="d-flex align-items-center gap-2 bg-white p-2 rounded-4 shadow-sm border">
            <form action="{{ route('dashboard') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-0 pe-2"><i class="ti ti-filter text-muted"></i></span>
                    <select name="stack_id" class="form-select border-0 bg-transparent fw-bold text-dark py-1" style="min-width: 150px; cursor: pointer; box-shadow: none;" onchange="this.form.submit()">
                        <option value="" {{ $selectedStackId == '' ? 'selected' : '' }}>All Stacks</option>
                        @foreach($stacks as $s)
                            <option value="{{ $s->id }}" {{ $selectedStackId == $s->id ? 'selected' : '' }}>{{ $s->stack_name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="vr mx-1"></div>

            <button id="btn-rca"
                class="btn {{ $isRcaOn ? 'btn-danger' : 'btn-dark' }} fw-bold rounded-pill px-4 btn-sm d-flex align-items-center gap-2"
                onclick="toggleRcaMode()">
                <i class="ti {{ $isRcaOn ? 'ti-player-stop' : 'ti-activity' }}"></i>
                <span id="text-rca">{{ $isRcaOn ? 'Stop RCA' : 'Start RCA' }}</span>
                <span id="loading-rca" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
        </div>
    </div>

    <!-- GRID SENSOR -->
    <div class="row g-4">
        @forelse($sensors as $sensor)
            @php
                $log = \App\Models\DasLog::where('sensor_config_id', $sensor->id)->latest('timestamp')->first();
                $measured = $log ? number_format($log->measured_value, 2) : '0.00';
                $raw = $log ? number_format($log->raw_value, 2) : '0.00';
            @endphp

            <div class="col-md-6 col-lg-3 col-xl-3">
                <div class="card card-sensor h-100 border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h6 class="param-name text-uppercase mb-0">{{ $sensor->parameter_name }}</h6>
                                @if(!$selectedStackId)
                                    <small class="text-muted" style="font-size: 10px;">{{ $sensor->stackConfig->stack_name ?? '-' }}</small>
                                @endif
                            </div>
                            <div class="live-badge {{ $sensor->status == 'Inactive' ? 'offline' : '' }}">
                                <div class="live-dot"></div> {{ $sensor->status == 'Active' ? 'LIVE' : 'OFFLINE' }}
                            </div>
                        </div>

                        <div class="d-flex align-items-end gap-2 mb-2">
                            <span class="value-display" id="measured-{{ $sensor->id }}">{{ $measured }}</span>
                            <span class="unit-display mb-3">{{ $sensor->unit->name ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="raw-container">
                        <div class="raw-label"><i class="ti ti-bolt text-warning"></i> Input Signal</div>
                        <div class="raw-value" id="raw-{{ $sensor->id }}">{{ $raw }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 bg-white shadow-sm rounded-4 text-center py-5">
                    <div class="card-body">
                        <div class="bg-light-primary text-primary p-4 rounded-circle d-inline-flex mb-3">
                            <i class="ti ti-database-off fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark">No Sensors Found</h4>
                        <p class="text-muted">There are no active sensors configured for this selection.</p>
                        <a href="{{ route('sensor-config.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">Add Sensor</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection

@push('scripts')
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const stackId = urlParams.get('stack_id') || "";

        function fetchRealtimeData() {
            $.ajax({
                url: "{{ route('dashboard') }}",
                type: "GET",
                data: { stack_id: stackId },
                success: function(response) {
                    if(Array.isArray(response)){
                        response.forEach(function(item) {
                            $('#measured-' + item.sensor_id).text(item.measured);
                            $('#raw-' + item.sensor_id).text(item.raw);
                        });
                    }
                }
            });
        }
        setInterval(fetchRealtimeData, 2000);
    });

    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true,
        didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
    });

    function toggleRcaMode() {
        $('#btn-rca').prop('disabled', true);
        $('#loading-rca').removeClass('d-none');

        $.ajax({
            url: "{{ route('dashboard.toggle-rca') }}",
            type: "POST",
            success: function(response) {
                if(response.is_rca_mode) {
                    $('#btn-rca').removeClass('btn-dark').addClass('btn-danger');
                    $('#text-rca').text('Stop RCA');
                    $('#btn-rca i').removeClass('ti-activity').addClass('ti-player-stop');

                    $('#dashboard-wrapper').addClass('mode-rca-active');
                    $('.dashboard-title').text('AUDIT MODE ACTIVE');

                    Toast.fire({ icon: 'warning', title: 'RCA Mode Activated' });
                } else {
                    $('#btn-rca').removeClass('btn-danger').addClass('btn-dark');
                    $('#text-rca').text('Start RCA');
                    $('#btn-rca i').removeClass('ti-player-stop').addClass('ti-activity');

                    $('#dashboard-wrapper').removeClass('mode-rca-active');
                    $('.dashboard-title').text('Monitoring Dashboard');

                    Toast.fire({ icon: 'success', title: 'RCA Stopped' });
                }
            },
            error: function(xhr) { Toast.fire({ icon: 'error', title: 'Action Failed' }); },
            complete: function() {
                $('#btn-rca').prop('disabled', false);
                $('#loading-rca').addClass('d-none');
            }
        });
    }
</script>
@endpush