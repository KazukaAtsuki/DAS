@extends('layouts.master')

@push('styles')
<style>
    :root { --das-teal: #009688; --das-dark: #1A1A1A; }
    .page-container { padding-top: 100px; padding-bottom: 50px; }

    .card-modern { border: none; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }

    /* Table Styling */
    .table-modern thead th {
        background-color: #f8f9fa; color: var(--das-dark);
        font-weight: 700; text-transform: uppercase; font-size: 0.85rem;
        border-bottom: 2px solid var(--das-teal); padding: 15px;
    }

    /* Input Date Modern */
    .input-date {
        border: 2px solid #e0e0e0; border-radius: 8px;
        padding: 8px 12px; font-weight: 600; color: var(--das-dark);
    }
    .input-date:focus { border-color: var(--das-teal); outline: none; }

    .btn-teal { background-color: var(--das-teal); color: white; border: none; }
    .btn-teal:hover { background-color: #00796b; color: white; }

    .stack-select { border: 2px solid #e0e0e0; border-radius: 8px; font-weight: 600; }

    .page-item.active .page-link { background-color: var(--das-teal) !important; border-color: var(--das-teal) !important; }
    .page-link { color: var(--das-teal) !important; }
</style>
@endpush

@section('content')
<div class="container-fluid page-container">

    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <div class="bg-light-primary p-2 rounded-circle me-3 text-primary">
                <i class="ti ti-file-analytics fs-1" style="color: #009688;"></i>
            </div>
            <div>
                <h3 class="fw-bolder mb-0 text-dark">DAS Logs Data</h3>
                <small class="text-muted">Real-time monitoring history from sensors.</small>
            </div>
        </div>
        <!-- Stack Filter -->
        <div class="d-flex align-items-center">
            <span class="fw-bold me-2 text-muted"><i class="ti ti-filter"></i> Stack:</span>
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

                <!-- Filter Tanggal -->
                <div class="col-md-8 d-flex align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <span class="text-muted fw-bold me-2 small text-uppercase">From:</span>
                        <input type="date" id="startDate" class="form-control input-date" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-muted fw-bold me-2 small text-uppercase">To:</span>
                        <input type="date" id="endDate" class="form-control input-date" value="{{ date('Y-m-d') }}">
                    </div>
                    <button id="btnFilterDate" class="btn btn-outline-dark d-flex align-items-center gap-1 fw-bold">
                        <i class="ti ti-search"></i> Filter
                    </button>
                    <!-- Tombol Reset -->
                    <button id="btnResetDate" class="btn btn-light d-flex align-items-center gap-1 text-muted">
                        <i class="ti ti-rotate"></i> Reset
                    </button>
                </div>

                <!-- Export Button -->
                <div class="col-md-4 text-end">
                    <a href="#" id="btnExport" class="btn btn-teal d-inline-flex align-items-center gap-2 rounded-pill px-4 text-decoration-none shadow-sm">
                        <i class="ti ti-download"></i> Export Data
                    </a>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table align-middle table-hover table-modern" id="logsTable" style="width:100%">
                    <thead>
                        <tr>
                            <th class="border-bottom-0">#ID</th>
                            <th class="border-bottom-0">Timestamp</th>
                            <th class="border-bottom-0">Sensor</th>
                            <th class="border-bottom-0">Measured</th>
                            <th class="border-bottom-0">Raw</th>
                            <th class="border-bottom-0">Status DIS</th>
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
            var table = $('#logsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('logs.index') }}",
                    data: function (d) {
                        d.stack_id = $('#filterStack').val();
                        // Kirim Data Tanggal ke Controller
                        d.start_date = $('#startDate').val();
                        d.end_date = $('#endDate').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'timestamp', name: 'timestamp' },
                    { data: 'sensor_name', name: 'sensorConfig.parameter_name', render: function(data) { return '<span class="fw-bolder text-dark">' + data + '</span>'; } },
                    { data: 'measured_value', name: 'measured_value' },
                    { data: 'raw_value', name: 'raw_value' },
                    { data: 'status_sent_dis', name: 'status_sent_dis' },
                ],
                order: [[0, 'asc']],
                language: { search: "_INPUT_", searchPlaceholder: "Search records..." }
            });

            // Update Link Export (Termasuk Tanggal)
            function updateExportLink() {
                var stackId = $('#filterStack').val();
                var start = $('#startDate').val();
                var end = $('#endDate').val();

                // Tambahkan parameter tanggal ke URL Export
                var url = "{{ route('logs.export') }}?stack_id=" + stackId + "&start_date=" + start + "&end_date=" + end;
                $('#btnExport').attr('href', url);
            }

            updateExportLink();

            // Event: Ganti Stack
            $('#filterStack').change(function(){
                table.draw();
                updateExportLink();
            });

            // Event: Klik Tombol Filter Tanggal
            $('#btnFilterDate').click(function(){
                table.draw(); // Reload tabel dengan tanggal baru
                updateExportLink();
            });

            // Event: Klik Reset
            $('#btnResetDate').click(function(){
                // Kembalikan ke tanggal hari ini (atau kosongkan)
                // $('#startDate').val('');
                // $('#endDate').val('');

                // Reload tabel
                table.draw();
                updateExportLink();
            });
        });
    </script>
@endpush