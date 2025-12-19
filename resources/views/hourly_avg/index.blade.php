@extends('layouts.master')

@push('styles')
<style>
    :root { --das-teal: #009688; --das-dark: #1A1A1A; --das-blue: #3b82f6; }

    .page-container { padding-top: 100px; padding-bottom: 50px; }

    .card-modern { border: none; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }

    .table-modern thead th {
        background-color: #f8f9fa; color: var(--das-dark); font-weight: 700;
        text-transform: uppercase; font-size: 0.85rem; border-bottom: 2px solid var(--das-teal);
        padding: 15px;
    }

    .btn-teal { background-color: var(--das-teal); color: white; border: none; }
    .btn-teal:hover { background-color: #00796b; color: white; }

    .btn-outline-teal { border: 1px solid var(--das-teal); color: var(--das-teal); background: transparent; }
    .btn-outline-teal:hover { background-color: var(--das-teal); color: white; }

    .btn-simpel { background: linear-gradient(45deg, #2196F3, #1976D2); color: white; border: none; }
    .btn-simpel:hover { background: linear-gradient(45deg, #1976D2, #1565C0); color: white; transform: translateY(-1px); }

    .stack-select { border: 2px solid #e0e0e0; border-radius: 8px; font-weight: 600; color: var(--das-dark); }
    .stack-select:focus { border-color: var(--das-teal); box-shadow: none; }

    .page-item.active .page-link { background-color: var(--das-teal) !important; border-color: var(--das-teal) !important; }
    .page-link { color: var(--das-teal) !important; }
</style>
@endpush

@section('content')
<div class="container-fluid page-container">

    <!-- HEADER SECTION -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <div class="bg-light-primary p-2 rounded-circle me-3 text-primary">
                <i class="ti ti-clock-hour-4 fs-1" style="color: #009688;"></i>
            </div>
            <div>
                <h3 class="fw-bolder mb-0 text-dark">Hourly Average</h3>
                <small class="text-muted">1-Hour aggregated measurement data.</small>
            </div>
        </div>
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

            <!-- ACTION TOOLBAR (FILTER TANGGAL) -->
            <div class="row align-items-center mb-4 g-2">
                <div class="col-md-8 d-flex align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <span class="text-muted fw-bold me-2 small text-uppercase">From:</span>
                        <input type="date" id="startDate" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-muted fw-bold me-2 small text-uppercase">To:</span>
                        <input type="date" id="endDate" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <button id="btnFilterDate" class="btn btn-outline-dark fw-bold"><i class="ti ti-search"></i> Filter</button>
                    <button id="btnResetDate" class="btn btn-light text-muted"><i class="ti ti-rotate"></i> Reset</button>
                </div>

                <!-- TOMBOL EXPORT -->
                <div class="col-md-4 text-end d-flex gap-2 justify-content-end">
                    <a href="#" id="btnExportSimpel" class="btn btn-simpel rounded-pill px-3 text-decoration-none shadow-sm font-sm">
                        <i class="ti ti-file-description"></i> SIMPEL
                    </a>
                    <a href="#" id="btnExportExcel" class="btn btn-teal rounded-pill px-3 text-decoration-none shadow-sm font-sm">
                        <i class="ti ti-download"></i> Excel
                    </a>
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
                        // Kirim data tanggal ke controller
                        d.start_date = $('#startDate').val();
                        d.end_date = $('#endDate').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'timestamp', name: 'timestamp' },
                    { data: 'sensor_name', name: 'sensorConfig.parameter_name', render: function(data) { return '<span class="fw-bolder text-dark">' + data + '</span>'; } },
                    { data: 'measured_value', name: 'measured_value' },
                    { data: 'corrected_value', name: 'corrected_value', render: function(data) { return '<span class="fw-bold text-primary" style="color: #009688 !important;">' + data + '</span>'; } },
                ],
                order: [[0, 'asc']],
                language: { search: "_INPUT_", searchPlaceholder: "Search hourly data..." }
            });

            // Update Link Export (Dynamic)
            function updateExportLink() {
                var stackId = $('#filterStack').val();
                var start = $('#startDate').val();
                var end = $('#endDate').val();

                // Buat Query String
                var query = "?stack_id=" + stackId + "&start_date=" + start + "&end_date=" + end;

                $('#btnExportExcel').attr('href', "{{ route('hourly.export.excel') }}" + query);
                $('#btnExportSimpel').attr('href', "{{ route('hourly.export.simpel') }}" + query);
            }

            updateExportLink();

            // Event Listeners
            $('#filterStack').change(function(){
                table.draw();
                updateExportLink();
            });

            $('#btnFilterDate').click(function(){
                table.draw();
                updateExportLink();
            });

            $('#btnResetDate').click(function(){
                // Reset tanggal ke hari ini (opsional)
                // $('#startDate').val(''); $('#endDate').val('');
                table.draw();
                updateExportLink();
            });
        });
    </script>
@endpush