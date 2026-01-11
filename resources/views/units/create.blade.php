@extends('layouts.master')

@section('content')
<div class="container-fluid" style="padding-top: 100px;"> <!-- FIX PADDING TOP -->

    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- Tampilkan Alert Error jika Verifikasi Gagal -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-alert-triangle fs-5 me-2"></i>
                        <div>
                            <strong>Authorization Error!</strong><br>
                            {{ session('error') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2 rounded-circle" style="background-color: #e0f2f1;">
                            <i class="ti ti-scale fs-3" style="color: #009688;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Create New Unit</h5>
                            <p class="text-muted small mb-0">Centralized Authorization System.</p>
                        </div>
                    </div>
                    <a href="{{ route('units.index') }}" class="btn btn-light text-muted btn-sm rounded-pill px-3">
                        <i class="ti ti-x fs-5"></i>
                    </a>
                </div>

                <div class="card-body p-4">
                    <!-- Pastikan Action dan Method Benar -->
                    <form action="{{ route('units.store') }}" method="POST" id="formUnit">
                        @csrf <!-- TOKEN CSRF -->

                        <!-- Field Input Nama Unit -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase">Unit Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-ruler"></i></span>
                                <input type="text" name="name" class="form-control border-start-0 bg-light ps-0"
                                       placeholder="e.g. mg/m3" value="{{ old('name') }}" required autofocus>
                            </div>
                        </div>

                        <!-- SECTION VERIFIKASI -->
                        <div class="mb-4 p-3 rounded-4" style="background-color: #f0fdfa; border: 2px dashed #009688;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="ti ti-shield-check fs-5" style="color: #009688;"></i>
                                <label class="form-label fw-bold text-dark mb-0 small text-uppercase">Security Authorization</label>
                            </div>
                            <p class="text-muted mb-3" style="font-size: 0.75rem;">
                                Masukkan 6 digit kode dari <b>Dashboard Aktivasi</b>. Jika Anda menunggu terlalu lama, klik "Refresh Token" sebelum simpan.
                            </p>
                            

                            <div class="input-group shadow-sm rounded-3 overflow-hidden">
                                <input type="text" name="verif_code" id="verif_code"
                                       class="form-control border-0 text-center fw-bold fs-5"
                                       placeholder="· · · · · ·"
                                       maxlength="6"
                                       inputmode="numeric"
                                       required
                                       style="background-color: #fff; letter-spacing: 10px; color: #009688; height: 55px;">
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted" style="font-size: 0.65rem;">
                                    <i class="ti ti-clock me-1"></i> Sesi Anda tetap aktif
                                </small>
                                <!-- Tombol Refresh halaman jika merasa sesi sudah basi -->
                                <small class="text-primary fw-bold" style="font-size: 0.65rem; cursor: pointer;" onclick="window.location.reload();">
                                    <i class="ti ti-refresh me-1"></i> Refresh Form (Cegah 419)
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                            <a href="{{ route('units.index') }}" class="btn btn-light text-muted fw-bold rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm"
                                    style="background-color: #009688; border: none;" id="btnSubmit">
                                <i class="ti ti-lock-open me-1"></i> Verify & Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Mencegah input selain angka
    document.getElementById('verif_code').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // 2. Cegah Double Submit
    document.getElementById('formUnit').addEventListener('submit', function() {
        let btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<i class="ti ti-loader-2 spin-anim"></i> Validating...';
    });

    // 3. FITUR ANTI-419: Ping server setiap 5 menit agar sesi tidak mati
    setInterval(function() {
        fetch('/refresh-csrf').catch(e => console.log('Ping session'));
    }, 300000); // 5 Menit
</script>

<style>
    .spin-anim {
        animation: rotate 1s linear infinite;
        display: inline-block;
    }
    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endsection