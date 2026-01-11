<?php

namespace App\Http\Controllers;

// Import yang kurang tadi:
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Fungsi untuk verifikasi kode ke Python API
     */
    protected function checkVerification($code)
    {
        try {
            // Laravel memanggil Python API
            $response = Http::withHeaders([
                'X-API-KEY' => env('PYTHON_API_KEY')
            ])->post(env('PYTHON_API_URL') . "/verify-code", [
                'logger_id' => 'LOG-001', // Pastikan ID ini terdaftar di Python
                'input_code' => $code
            ]);

            // Ambil data 'valid' dari JSON response Python
            return $response->json()['valid'] ?? false;

        } catch (\Exception $e) {
            // Jika koneksi ke Python gagal, anggap tidak valid
            return false;
        }
    }
}