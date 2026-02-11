@extends('layouts.master')

@push('styles')
<style>
    :root {
        --das-teal: #009688;
        --das-dark: #1e293b;
        --das-gray: #64748b;
        --das-bg-soft: #f1f5f9;
        --rca-bg-dark: #450a0a;
        --rca-accent: #ef4444;
    }

    /* --- LOGIKA VISUAL ALARM KUNING (WARNING) --- */
    .alarm-warning {
        color: #f59e0b !important; /* Kuning/Orange */
        text-shadow: 0 0 10px rgba(245, 158, 11, 0.2);
    }
    .card-warning {
        border: 2px solid #f59e0b !important;
        background-color: #fffbeb !important; /* Kuning pudar */
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.1) !important;
    }

    /* --- LOGIKA VISUAL ALARM MERAH (HIGH LIMIT) --- */
    .alarm-danger {
        color: #ef4444 !important;
        animation: alarm-blink 1s infinite;
        text-shadow: 0 0 15px rgba(239, 68, 68, 0.4);
    }
    .card-alarm {
        border: 2px solid #ef4444 !important;
        background-color: #fff5f5 !important;
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.2) !important;
    }

    @keyframes alarm-blink {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    /* Container & Card Styling */
    .dashboard-container { padding-top: 100px; padding-bottom: 50px; max-width: 1600px; margin: 0 auto; transition: all 0.5s; }
    .card-sensor { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05); transition: all 0.3s; position: relative; overflow: hidden; }
    .card-sensor::after { content: ''; position: absolute; top: 0; left: 20px; right: 20px; height: 3px; background: var(--das-teal); border-radius: 0 0 4px 4px; }

    /* Typography */
    .param-name { color: var(--das-gray); font-size: 0.85rem; letter-spacing: 1.2px; font-weight: 700; }
    .value-display { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--das-dark); letter-spacing: -2px; line-height: 1; font-size: 3.5rem; transition: all 0.3s; }
    .unit-display { font-size: 1rem; font-weight: 600; color: var(--das-gray); background: #f1f5f9; padding: 2px 8px; border-radius: 6px; }

    .raw-container { background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; }
    .raw-value { font-family: 'Courier New', monospace; font-weight: 700; color: var(--das-dark); font-size: 0.9rem; }

    .live-badge { background-color: rgba(34, 197, 94, 0.1); color: #16a34a; font-size: 0.7rem; font-weight: 800; padding: 4px 10px; border-radius: 30px; display: flex; align-items: center; gap: 6px; }
    .badge-warning { background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-danger { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .live-dot { width: 6px; height: 6px; background-color: #22c55e; border-radius: 50%; animation: pulse-green 1.5s infinite; }
    @keyframes pulse-green { 0% { transform: scale(0.95); } 70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(34, 197, 94, 0); } 100% { transform: scale(0.95); } }
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid dashboard-container">

    <!-- HEADER -->
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between mb-5 gap-3">
        <div>
            <h2 class="fw-bolder text-dark mb-1">Monitoring Dashboard</h2>
            <p class="text-muted small mb-0">Real-time data acquisition and threshold monitoring.</p>
        </div>

        <div class="d-flex align-items-center gap-2 bg-white p-2 rounded-4 shadow-sm border">
            <form action="{{ route('dashboard') }}" method="GET" class="d-flex">
                <select name="stack_id" class="form-select border-0 bg-transparent fw-bold" onchange="this.form.submit()">
                    <option value="">All Stacks</option>
                    @foreach($stacks as $s)
                        <option value="{{ $s->id }}" {{ $selectedStackId == $s->id ? 'selected' : '' }}>{{ $s->stack_name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- GRID SENSOR -->
    <div class="row g-4">
        @forelse($sensors as $sensor)
            <div class="col-md-6 col-lg-3 col-xl-3">
                <div class="card card-sensor h-100 border-0" id="card-{{ $sensor->id }}">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h6 class="param-name text-uppercase mb-0">{{ $sensor->parameter_name }}</h6>
                                <small class="text-muted" style="font-size: 9px;">ID: {{ $sensor->sensor_code }}</small>
                            </div>
                            <div class="live-badge" id="badge-{{ $sensor->id }}">
                                <div class="live-dot"></div> LIVE
                            </div>
                        </div>

                        <div class="d-flex align-items-end gap-2 mb-2">
                            <span class="value-display" id="measured-{{ $sensor->id }}">0.00</span>
                            <span class="unit-display mb-3">{{ $sensor->unit->name ?? '-' }}</span>
                        </div>

                        <!-- Info Batas (Limit & Warnings) -->
                        <div class="d-flex gap-2 mt-2">
                            <small class="text-muted" style="font-size: 9px;">L: {{ $sensor->limit_value ?? '0' }}</small>
                            <small class="text-muted" style="font-size: 9px;">W1: {{ $sensor->warning_1 ?? '0' }}</small>
                            <small class="text-muted" style="font-size: 9px;">W2: {{ $sensor->warning_2 ?? '0' }}</small>
                        </div>
                    </div>
                    <div class="raw-container">
                        <div class="raw-label small text-uppercase" style="font-size: 0.6rem;">Input Signal</div>
                        <div class="raw-value" id="raw-{{ $sensor->id }}">0.00</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No active sensors found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $(document).ready(function() {
        const stackId = new URLSearchParams(window.location.search).get('stack_id') || "";

        function fetchRealtimeData() {
            $.ajax({
                url: "{{ route('dashboard') }}",
                type: "GET",
                data: { stack_id: stackId },
                success: function(response) {
                    if(Array.isArray(response)){
                        response.forEach(function(item) {
                            let measuredEl = $('#measured-' + item.sensor_id);
                            let cardEl = $('#card-' + item.sensor_id);
                            let badgeEl = $('#badge-' + item.badge_id || '#badge-' + item.sensor_id);

                            measuredEl.text(item.measured);
                            $('#raw-' + item.sensor_id).text(item.raw);

                            // --- LOGIKA ALARM MULTI-LEVEL ---
                            let val = parseFloat(item.measured);
                            let limit = parseFloat(item.limit);
                            let w1 = parseFloat(item.warning1);
                            let w2 = parseFloat(item.warning2);

                            // Reset State Dulu
                            measuredEl.removeClass('alarm-danger alarm-warning');
                            cardEl.removeClass('card-alarm card-warning');
                            badgeEl.removeClass('badge-danger badge-warning');

                            if (limit > 0 && val > limit) {
                                // 1. TINGKAT KRITIS (MERAH)
                                measuredEl.addClass('alarm-danger');
                                cardEl.addClass('card-alarm');
                                badgeEl.addClass('badge-danger').html('<i class="ti ti-alert-triangle"></i> HIGH ALARM');
                            }
                            else if ((w2 > 0 && val > w2) || (w1 > 0 && val > w1)) {
                                // 2. TINGKAT PERINGATAN (KUNING)
                                measuredEl.addClass('alarm-warning');
                                cardEl.addClass('card-warning');
                                badgeEl.addClass('badge-warning').html('<i class="ti ti-alert-circle"></i> WARNING');
                            }
                            else {
                                // 3. NORMAL (HIJAU)
                                badgeEl.html('<div class="live-dot"></div> LIVE');
                            }
                        });
                    }
                }
            });
        }
        setInterval(fetchRealtimeData, 3000);
    });
</script>
@endpush