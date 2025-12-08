@extends('layouts.master')

@push('styles')
<style>
    /* --- CUSTOM CSS SENSOR CONFIG --- */
    :root {
        --das-teal: #009688;
        --das-dark: #1A1A1A;
    }

    /* Card Modern */
    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    /* Table Header Modern */
    .table-modern thead th {
        background-color: #f8f9fa;
        color: var(--das-dark);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border-bottom: 2px solid var(--das-teal);
        padding: 15px;
        white-space: nowrap; /* Agar header tidak turun baris sembarangan */
    }

    /* Tombol Utama */
    .btn-teal {
        background-color: var(--das-teal);
        color: white;
        border: none;
        box-shadow: 0 4px 10px rgba(0, 150, 136, 0.3);
    }
    .btn-teal:hover {
        background-color: #00796b;
        color: white;
        transform: translateY(-2px);
    }

    /* Pagination Active State */
    .page-item.active .page-link {
        background-color: var(--das-teal) !important;
        border-color: var(--das-teal) !important;
    }
    .page-link { color: var(--das-teal) !important; }
</style>
@endpush

@section('content')
<!-- FIX: Padding Top 100px agar konten tidak tertutup Header -->
<div class="container-fluid" style="padding-top: 100px;">

    <!-- HEADER PAGE -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="bg-light-primary p-2 rounded-circle me-3 text-primary">
                <i class="ti ti-broadcast fs-1" style="color: #009688;"></i>
            </div>
            <div>
                <h3 class="fw-bolder mb-0 text-dark">Sensor Configuration</h3>
                <small class="text-muted">Manage sensors and parameters connected to stacks.</small>
            </div>
        </div>

        <a href="{{ route('sensor-config.create') }}" class="btn btn-teal d-flex align-items-center gap-2 rounded-pill px-4">
            <i class="ti ti-plus"></i> Create New Sensor
        </a>
    </div>

    <!-- ALERT SUCCESS -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
            <i class="ti ti-circle-check fs-5 me-2"></i>
            <div>{{ $message }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- MAIN CARD -->
    <div class="card card-modern">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle table-hover table-modern" id="sensorTable" style="width:100%">
                    <thead>
                        <tr>
                            <th class="border-bottom-0 text-center"><i class="ti ti-settings"></i> Action</th>
                            <th class="border-bottom-0"><i class="ti ti-building-factory-2 me-1"></i> Stack</th>
                            <th class="border-bottom-0"><i class="ti ti-tag me-1"></i> Name</th>
                            <th class="border-bottom-0"><i class="ti ti-scale me-1"></i> Unit</th>
                            <!-- Gabungan Extra & O2 -->
                            <th class="border-bottom-0"><i class="ti ti-list-details me-1"></i> Extra Parameter</th>
                            <th class="border-bottom-0"><i class="ti ti-link me-1"></i> Has Ref?</th>
                            <th class="border-bottom-0"><i class="ti ti-activity me-1"></i> Status</th>
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
            $('#sensorTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sensor-config.index') }}",
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center" },
                    {
                        data: 'stack_name',
                        name: 'stackConfig.stack_name',
                        render: function(data) {
                            return '<span class="text-dark fw-bolder">' + data + '</span>';
                        }
                    },
                    { data: 'parameter_name', name: 'parameter_name' },
                    { data: 'unit_name', name: 'unit.name' },
                    { data: 'extra_badges', name: 'extra_parameter', orderable: false, searchable: false },
                    { data: 'has_parameter_reference', name: 'has_parameter_reference' },
                    { data: 'status', name: 'status' },
                ]
            });
        });
    </script>
@endpush