@extends('layouts.master')

@push('styles')
<style>
    /* --- CUSTOM CSS FOR HOURLY PAGE --- */
    :root {
        --das-teal: #009688;
        --das-dark: #1A1A1A;
        --das-blue: #3b82f6; /* Warna biru untuk tombol SIMPEL */
    }

    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .table-modern thead th {
        background-color: #f8f9fa;
        color: var(--das-dark);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border-bottom: 2px solid var(--das-teal);
        padding: 15px;
    }

    /* Buttons */
    .btn-teal {
        background-color: var(--das-teal);
        color: white;
        border: none;
    }
    .btn-teal:hover {
        background-color: #00796b;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 150, 136, 0.3);
    }

    .btn-outline-teal {
        border: 1px solid var(--das-teal);
        color: var(--das-teal);
        background: transparent;
    }
    .btn-outline-teal:hover {
        background-color: var(--das-teal);
        color: white;
    }

    /* Tombol Spesial SIMPEL */
    .btn-simpel {
        background: linear-gradient(45deg, #2196F3, #1976D2);
        color: white;
        border: none;
        box-shadow: 0 4px 10px rgba(33, 150, 243, 0.3);
    }
    .btn-simpel:hover {
        background: linear-gradient(45deg, #1976D2, #1565C0);
        color: white;
        transform: translateY(-1px);
    }

    .stack-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-weight: 600;
        color: var(--das-dark);
    }
    .stack-select:focus { border-color: var(--das-teal); box-shadow: none; }

    .page-item.active .page-link {
        background-color: var(--das-teal) !important;
        border-color: var(--das-teal) !important;
    }
    .page-link { color: var(--das-teal) !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- HEADER SECTION -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <!-- Ikon Jam (Hourly) -->
            <div class="bg-light-primary p-2 rounded-circle me-3 text-primary">
                <i class="ti ti-clock-hour-4 fs-1" style="color: #009688;"></i>
            </div>
            <div>
                <h3 class="fw-bolder mb-0 text-dark">Hourly Average</h3>
                <small class="text-muted">1-Hour aggregated measurement data.</small>
            </div>
        </div>

        <!-- STACK FILTER -->
        <div class="d-flex align-items-center">
            <span class="fw-bold me-2 text-muted"><i class="ti ti-filter"></i> Select Stack:</span>
            <select id="filterStack" class="form-select stack-select w-auto" style="min-width: 200px;">
                @foreach($stacks as $stack)
                    <option value="{{ $stack->id }}">{{ $stack->stack_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- MAIN CARD -->
    <div class="card card-modern">
        <div class="card-body p-4">

            <!-- ACTION TOOLBAR -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">

                <button class="btn btn-outline-teal d-flex align-items-center gap-2">
                    <i class="ti ti-adjustments-horizontal"></i> Filter Range
                </button>

                <!-- Group Tombol Export -->
                <div class="d-flex gap-2">
                    <!-- Tombol SIMPEL -->
                    <button class="btn btn-simpel d-flex align-items-center gap-2 rounded-pill px-4">
                        <i class="ti ti-file-description"></i> Export SIMPEL
                    </button>
                    <!-- Tombol Export Biasa -->
                    <button class="btn btn-teal d-flex align-items-center gap-2 rounded-pill px-4">
                        <i class="ti ti-download"></i> Export Excel
                    </button>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table align-middle table-hover table-modern" id="hourlyTable" style="width:100%">
                    <thead>
                        <tr>
                            <th class="border-bottom-0"><i class="ti ti-hash me-1"></i> ID</th>
                            <th class="border-bottom-0"><i class="ti ti-clock me-1"></i> Timestamp</th>
                            <th class="border-bottom-0"><i class="ti ti-broadcast me-1"></i> Sensor</th>
                            <th class="border-bottom-0"><i class="ti ti-ruler-2 me-1"></i> Measured</th>
                            <th class="border-bottom-0 text-primary" style="color: var(--das-teal) !important;"><i class="ti ti-check me-1"></i> Corrected</th>
                        </tr>
                    </thead>
                    <tbody class="text-dark fw-bold text-muted"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#hourlyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('hourly.index') }}",
                    data: function (d) {
                        d.stack_id = $('#filterStack').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'timestamp', name: 'timestamp' },
                    {
                        data: 'sensor_name',
                        name: 'sensorConfig.parameter_name',
                        render: function(data) {
                            return '<span class="fw-bolder text-dark">' + data + '</span>';
                        }
                    },
                    { data: 'measured_value', name: 'measured_value' },
                    { data: 'corrected_value', name: 'corrected_value' },
                ],
                order: [[1, 'desc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search hourly data..."
                }
            });

            // Reload saat ganti stack
            $('#filterStack').change(function(){
                table.draw();
            });
        });
    </script>
@endpush