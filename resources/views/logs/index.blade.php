@extends('layouts.master')

@push('styles')
<style>
    /* --- CUSTOM CSS FOR LOGS PAGE --- */
    :root {
        --das-teal: #009688;
        --das-dark: #1A1A1A;
    }

    /* Card Styling */
    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }

    /* Table Header */
    .table-modern thead th {
        background-color: #f8f9fa;
        color: var(--das-dark);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border-bottom: 2px solid var(--das-teal);
        padding-top: 15px;
        padding-bottom: 15px;
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

    /* Custom DataTables Pagination */
    .page-item.active .page-link {
        background-color: var(--das-teal) !important;
        border-color: var(--das-teal) !important;
    }
    .page-link {
        color: var(--das-teal) !important;
    }

    /* Select Filter Styling */
    .stack-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-weight: 600;
        color: var(--das-dark);
    }
    .stack-select:focus {
        border-color: var(--das-teal);
        box-shadow: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- HEADER SECTION -->
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

        <!-- STACK FILTER DROPDOWN -->
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <button class="btn btn-outline-teal d-flex align-items-center gap-2">
                    <i class="ti ti-adjustments-horizontal"></i> Advanced Filter
                </button>
                <button class="btn btn-teal d-flex align-items-center gap-2 rounded-pill px-4">
                    <i class="ti ti-download"></i> Export Data
                </button>
            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table align-middle table-hover table-modern" id="logsTable" style="width:100%">
                    <thead>
                        <tr>
                            <th class="border-bottom-0"><i class="ti ti-hash me-1"></i> ID</th>
                            <th class="border-bottom-0"><i class="ti ti-clock me-1"></i> Timestamp</th>
                            <th class="border-bottom-0"><i class="ti ti-broadcast me-1"></i> Sensor</th>
                            <th class="border-bottom-0"><i class="ti ti-ruler me-1"></i> Measured</th>
                            <th class="border-bottom-0"><i class="ti ti-binary me-1"></i> Raw</th>
                            <th class="border-bottom-0"><i class="ti ti-server me-1"></i> Status DIS</th>
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
    <!-- Pastikan urutan script benar (jQuery -> DataTable CSS -> DataTable JS) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Hapus jQuery jika sudah ada di master layout, jika belum biarkan -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> -->

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Inisialisasi DataTable
            var table = $('#logsTable').DataTable({
                processing: true,
                serverSide: true,
                // Hilangkan fitur search bawaan jika ingin tampilan lebih clean (opsional)
                // searching: false,
                ajax: {
                    url: "{{ route('logs.index') }}",
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
                    { data: 'raw_value', name: 'raw_value' },
                    { data: 'status_sent_dis', name: 'status_sent_dis' },
                ],
                order: [[1, 'desc']], // Default urut timestamp terbaru
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records..."
                }
            });

            // 2. Event Listener: Kalau Dropdown Stack ganti, refresh tabel
            $('#filterStack').change(function(){
                table.draw();
            });
        });
    </script>
@endpush