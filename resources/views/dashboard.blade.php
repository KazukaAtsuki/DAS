@extends('layouts.master')

@push('styles')
<style>
    :root {
        --das-teal: #009688;
        --das-dark: #1e293b;
        --das-gray: #64748b;
        --das-bg-soft: #f1f5f9;
    }

    /* Container Adjustment */
    .dashboard-container {
        padding-top: 100px;
        padding-bottom: 50px;
        max-width: 1600px;
        margin: 0 auto;
    }

    /* Modern Card Style */
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

    /* Glow Effect */
    .card-sensor::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top right, rgba(0, 150, 136, 0.05), transparent 40%);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .card-sensor:hover::before {
        opacity: 1;
    }

    /* Top Accent Line */
    .card-sensor::after {
        content: '';
        position: absolute;
        top: 0;
        left: 20px;
        right: 20px;
        height: 3px;
        background: var(--das-teal);
        border-radius: 0 0 4px 4px;
        opacity: 0.6;
    }

    /* Live Indicator */
    .live-badge {
        background-color: rgba(34, 197, 94, 0.1);
        color: #16a34a;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        gap: 6px;
        letter-spacing: 0.5px;
    }
    .live-badge.offline {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    .live-dot {
        width: 6px;
        height: 6px;
        background-color: #22c55e;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        animation: pulse-green 1.5s infinite;
    }
    .offline .live-dot {
        background-color: #ef4444;
        animation: none;
        box-shadow: none;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    /* Typography */
    .param-name {
        color: var(--das-gray);
        font-size: 0.85rem;
        letter-spacing: 1.2px;
        font-weight: 700;
    }
    .value-display {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        color: var(--das-dark);
        letter-spacing: -2px;
        line-height: 1;
        font-size: 3.5rem;
    }
    .unit-display {
        font-size: 1rem;
        font-weight: 600;
        color: var(--das-gray);
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 6px;
    }

    /* Footer Raw Box */
    .raw-container {
        background-color: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .raw-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .raw-value {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        color: var(--das-dark);
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid dashboard-container">

    <!-- HEADER & CONTROLS -->
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-5 gap-3">

        <!-- Title & Breadcrumb -->
        <div>
            <h2 class="fw-bolder text-dark mb-1" style="letter-spacing: -0.5px;">Monitoring Dashboard</h2>
            <div class="d-flex align-items-center gap-2 text-muted small">
                <span class="badge bg-white text-secondary border shadow-sm">
                    <i class="ti ti-server me-1"></i> System V3
                </span>
                <i class="ti ti-chevron-right" style="font-size: 10px;"></i>

                <!-- Menampilkan Nama Stack yang Sedang Dipilih -->
                @php
                    // Logic Judul: Kalau selectedStackId ada isinya, cari namanya. Kalau kosong/null, berarti "All Stacks"
                    $currentStackName = $selectedStackId
                        ? ($stacks->firstWhere('id', $selectedStackId)->stack_name ?? 'Unknown Stack')
                        : 'All Stacks Overview';
                @endphp
                <span class="badge bg-primary text-white shadow-sm px-3">
                    {{ $currentStackName }}
                </span>
            </div>
        </div>

        <!-- Controls -->
        <div class="d-flex align-items-center gap-2 bg-white p-2 rounded-4 shadow-sm border">
            <!-- Dropdown Filter -->
            <form action="{{ route('dashboard') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-0 pe-2"><i class="ti ti-filter text-muted"></i></span>
                    <select name="stack_id" class="form-select border-0 bg-transparent fw-bold text-dark py-1" style="min-width: 150px; cursor: pointer; box-shadow: none;" onchange="this.form.submit()">

                        <!-- OPTION: ALL STACKS -->
                        <option value="" {{ $selectedStackId == '' ? 'selected' : '' }}>All Stacks</option>

                        <!-- Loop Stack Lain -->
                        @foreach($stacks as $s)
                            <option value="{{ $s->id }}" {{ $selectedStackId == $s->id ? 'selected' : '' }}>
                                {{ $s->stack_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="vr mx-1"></div>

            <!-- RCA Button -->
            <button class="btn btn-dark fw-bold rounded-pill px-4 btn-sm d-flex align-items-center gap-2" style="background-color: var(--das-dark);">
                <i class="ti ti-activity"></i> Start RCA
            </button>
        </div>
    </div>

    <!-- GRID SENSOR -->
    <div class="row g-4">
        @forelse($sensors as $sensor)
            <!-- Kita buat logika manual untuk mengambil data log terakhir di view jika tidak pakai AJAX load awal -->
            @php
                $log = \App\Models\DasLog::where('sensor_config_id', $sensor->id)->latest('timestamp')->first();
                $measured = $log ? number_format($log->measured_value, 2) : '0.00';
                $raw = $log ? number_format($log->raw_value, 2) : '0.00';
            @endphp

            <div class="col-md-6 col-lg-3 col-xl-3">
                <div class="card card-sensor h-100 border-0">
                    <!-- Body: Value -->
                    <div class="card-body p-4">

                        <!-- Top Row: Parameter & Stack Name -->
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h6 class="param-name text-uppercase mb-0">{{ $sensor->parameter_name }}</h6>
                                <!-- Tampilkan nama stack kecil jika mode All Stacks -->
                                @if(!$selectedStackId)
                                    <small class="text-muted" style="font-size: 10px;">{{ $sensor->stackConfig->stack_name ?? '-' }}</small>
                                @endif
                            </div>

                            <!-- Status Badge -->
                            <div class="live-badge {{ $sensor->status == 'Inactive' ? 'offline' : '' }}">
                                <div class="live-dot"></div> {{ $sensor->status == 'Active' ? 'LIVE' : 'OFFLINE' }}
                            </div>
                        </div>

                        <!-- Value Row (Measured) -->
                        <div class="d-flex align-items-end gap-2 mb-2">
                            <span class="value-display" id="measured-{{ $sensor->id }}">
                                {{ $measured }}
                            </span>
                            <span class="unit-display mb-3">{{ $sensor->unit->name ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Footer: Raw Data -->
                    <div class="raw-container">
                        <div class="raw-label">
                            <i class="ti ti-bolt text-warning"></i> Input Signal
                        </div>
                        <div class="raw-value" id="raw-{{ $sensor->id }}">
                            {{ $raw }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- State Kosong -->
            <div class="col-12">
                <div class="card border-0 bg-white shadow-sm rounded-4 text-center py-5">
                    <div class="card-body">
                        <div class="bg-light-primary text-primary p-4 rounded-circle d-inline-flex mb-3">
                            <i class="ti ti-database-off fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark">No Sensors Found</h4>
                        <p class="text-muted">There are no active sensors configured for this selection.</p>
                        <a href="{{ route('sensor-config.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                            Add Sensor
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Ambil stack_id dari URL (bisa string kosong jika All)
        const urlParams = new URLSearchParams(window.location.search);
        const stackId = urlParams.get('stack_id') || "";

        // Fungsi Update Data
        function fetchRealtimeData() {
            $.ajax({
                url: "{{ route('dashboard') }}",
                type: "GET",
                data: {
                    stack_id: stackId // Kirim ID stack (atau kosong)
                },
                success: function(response) {
                    if(Array.isArray(response)){
                        response.forEach(function(item) {
                            // Update Angka di HTML
                            $('#measured-' + item.sensor_id).text(item.measured);
                            $('#raw-' + item.sensor_id).text(item.raw);
                        });
                    }
                },
                error: function(xhr) {
                    // console.log("Waiting for data...");
                }
            });
        }

        // Jalankan setiap 2 detik
        setInterval(fetchRealtimeData, 2000);
    });
</script>
@endpush