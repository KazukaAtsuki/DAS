@extends('layouts.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">Edit Sensor</h5>
                <a href="{{ route('sensor-config.index') }}" class="text-dark"><i class="ti ti-x fs-5"></i></a>
            </div>
            <div class="card-body">

                <form action="{{ route('sensor-config.update', $sensor->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- KOLOM KIRI -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Sensor Code *</label>
                                <input type="text" name="sensor_code" class="form-control" value="{{ $sensor->sensor_code }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Parameter ID *</label>
                                <input type="text" name="parameter_id" class="form-control" value="{{ $sensor->parameter_id }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Unit *</label>
                                <select name="unit_id" class="form-select" required>
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}" {{ $sensor->unit_id == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- TAMBAHAN: HIGH LIMIT (BAKU MUTU) -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-danger">High Limit (Baku Mutu)</label>
                                <input type="number" step="0.01" name="limit_value" class="form-control border-danger"
                                       value="{{ $sensor->limit_value }}" placeholder="Contoh: 50.00">
                                <div class="form-text text-muted">Jika nilai melebihi angka ini, dashboard akan merah.</div>
                            </div>
                            <!-- END TAMBAHAN -->

                            <div class="mb-3">
                                <label class="form-label fw-bold">Analyzer IP</label>
                                <input type="text" name="analyzer_ip" class="form-control" value="{{ $sensor->analyzer_ip }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Extra Parameter *</label>
                                <select name="extra_parameter" class="form-select" required>
                                    <option value="Non Extra Parameter" {{ $sensor->extra_parameter == 'Non Extra Parameter' ? 'selected' : '' }}>No</option>
                                    <option value="O2" {{ $sensor->extra_parameter == 'O2' ? 'selected' : '' }}>O2</option>
                                    <option value="Parameter RCA" {{ $sensor->extra_parameter == 'Parameter RCA' ? 'selected' : '' }}>Parameter RCA</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">O2 Correction *</label>
                                <select name="o2_correction" class="form-select" required>
                                    <option value="Non Correction" {{ $sensor->o2_correction == 'Non Correction' ? 'selected' : '' }}>No</option>
                                    <option value="O2 Correction" {{ $sensor->o2_correction == 'O2 Correction' ? 'selected' : '' }}>O2 Correction</option>
                                </select>
                            </div>
                        </div>

                        <!-- KOLOM KANAN -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Parameter Name *</label>
                                <input type="text" name="parameter_name" class="form-control" value="{{ $sensor->parameter_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Stack *</label>
                                <select name="stack_config_id" class="form-select" required>
                                    @foreach($stacks as $s)
                                        <option value="{{ $s->id }}" {{ $sensor->stack_config_id == $s->id ? 'selected' : '' }}>
                                            {{ $s->stack_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active" {{ $sensor->status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ $sensor->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Port</label>
                                <input type="number" name="port" class="form-control" value="{{ $sensor->port }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Has Parameter Reference? *</label>
                                <select name="has_parameter_reference" class="form-select" required>
                                    <option value="No" {{ $sensor->has_parameter_reference == 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Yes" {{ $sensor->has_parameter_reference == 'Yes' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Formula *</label>
                        <textarea name="formula" class="form-control" rows="2">{{ $sensor->formula }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Sensor</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection