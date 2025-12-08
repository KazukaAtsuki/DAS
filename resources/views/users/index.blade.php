@extends('layouts.master')

@push('styles')
<style>
    /* Custom CSS User Management */
    :root {
        --das-teal: #009688;
        --das-dark: #1A1A1A;
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
        white-space: nowrap;
    }
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
    .page-item.active .page-link {
        background-color: var(--das-teal) !important;
        border-color: var(--das-teal) !important;
    }
    .page-link { color: var(--das-teal) !important; }
</style>
@endpush

@section('content')
<div class="container-fluid" style="padding-top: 100px;">

    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="bg-light-primary p-2 rounded-circle me-3 text-primary">
                <i class="ti ti-users fs-1" style="color: #009688;"></i>
            </div>
            <div>
                <h3 class="fw-bolder mb-0 text-dark">User Management</h3>
                <small class="text-muted">Manage system administrators and operators.</small>
            </div>
        </div>

        <a href="{{ route('users.create') }}" class="btn btn-teal d-flex align-items-center gap-2 rounded-pill px-4">
            <i class="ti ti-user-plus"></i> Add New User
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
            <i class="ti ti-circle-check fs-5 me-2"></i>
            <div>{{ $message }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card card-modern">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle table-hover table-modern" id="userTable" style="width:100%">
                    <thead>
                        <tr>
                            <th class="border-bottom-0 text-center"><i class="ti ti-settings"></i> Action</th>
                            <th class="border-bottom-0"><i class="ti ti-user me-1"></i> Name</th>
                            <th class="border-bottom-0"><i class="ti ti-mail me-1"></i> Email</th>
                            <th class="border-bottom-0"><i class="ti ti-shield-lock me-1"></i> Role</th>
                            <th class="border-bottom-0"><i class="ti ti-calendar me-1"></i> Created At</th>
                            <th class="border-bottom-0"><i class="ti ti-clock me-1"></i> Updated</th>
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
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center" },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data) {
                            return '<span class="fw-bolder text-dark">' + data + '</span>';
                        }
                    },
                    { data: 'email', name: 'email' },
                    {
                        data: 'role',
                        name: 'role',
                        render: function(data) {
                            if(data === 'Administrator') {
                                return '<span class="badge bg-light-primary text-primary border border-primary px-3 rounded-pill">Administrator</span>';
                            } else {
                                return '<span class="badge bg-light-secondary text-secondary border border-secondary px-3 rounded-pill">Operator</span>';
                            }
                        }
                    },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                ]
            });
        });
    </script>
@endpush