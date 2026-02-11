@extends('layouts.master')

@section('content')
<div class="container-fluid" style="padding-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center pt-4 px-4">
                    <h5 class="mb-0 fw-bold text-dark">Edit Sensor</h5>
                    <a href="{{ route('sensor-config.index') }}" class="btn btn-light btn-sm rounded-pill"><i class="ti ti-x fs-5"></i></a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('sensor-config.update', $sensor->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- KOLOM KIRI -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Sensor Code *</label>
                                    <input type="text" name="sensor_code" class="form-control bg-light" value="{{ $sensor->sensor_code }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Parameter ID *</label>
                                    <input type="text" name="parameter_id" class="form-control bg-light" value="{{ $sensor->parameter_id }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Unit *</label>
                                    <select name="unit_id" class="form-select bg-light" required>
                                        @foreach($units as $u)
                                            <option value="{{ $u->id }}" {{ $sensor->unit_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- HIGH LIMIT (BAKU MUTU) -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-danger small text-uppercase">High Limit (Baku Mutu)</label>
                                    <input type="number" step="0.01" name="limit_value" class="form-control border-danger bg-light" value="{{ $sensor->limit_value }}" placeholder="Contoh: 50.00">
                                </div>

                                <!-- WARNING 1 -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-warning small text-uppercase">Warning 1</label>
                                    <input type="number" step="0.01" name="warning_1" class="form-control border-warning bg-light" value="{{ $sensor->warning_1 }}" placeholder="Contoh: 40.00">
                                </div>

                                <!-- WARNING 2 -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-warning small text-uppercase">Warning 2</label>
                                    <input type="number" step="0.01" name="warning_2" class="form-control border-warning bg-light" value="{{ $sensor->warning_2 }}" placeholder="Contoh: 45.00">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Analyzer IP</label>
                                    <input type="text" name="analyzer_ip" class="form-control bg-light" value="{{ $sensor->analyzer_ip }}">
                                </div>
                            </div>

                            <!-- KOLOM KANAN (TETAP SAMA) -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Parameter Name *</label>
                                    <input type="text" name="parameter_name" class="form-control bg-light" value="{{ $sensor->parameter_name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Stack *</label>
                                    <select name="stack_config_id" class="form-select bg-light" required>
                                        @foreach($stacks as $s)
                                            <option value="{{ $s->id }}" {{ $sensor->stack_config_id == $s->id ? 'selected' : '' }}>{{ $s->stack_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Status</label>
                                    <select name="status" class="form-select bg-light">
                                        <option value="Active" {{ $sensor->status == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ $sensor->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Port</label>
                                    <input type="number" name="port" class="form-control bg-light" value="{{ $sensor->port }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Has Parameter Reference? *</label>
                                    <select name="has_parameter_reference" class="form-select bg-light" required>
                                        <option value="No" {{ $sensor->has_parameter_reference == 'No' ? 'selected' : '' }}>No</option>
                                        <option value="Yes" {{ $sensor->has_parameter_reference == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Formula *</label>
                            <textarea name="formula" class="form-control bg-light" rows="2">{{ $sensor->formula }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2" style="background-color: #009688; border: none;">Update Sensor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection