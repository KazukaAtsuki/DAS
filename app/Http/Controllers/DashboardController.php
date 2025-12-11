<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StackConfig;
use App\Models\SensorConfig;
use App\Models\DasLog;
use App\Models\GlobalConfig; // <--- 1. JANGAN LUPA IMPORT INI

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. Logika Filter Stack (Code Lama) ---
        $selectedStackId = $request->query('stack_id');

        $sensorQuery = SensorConfig::with(['unit', 'stackConfig']); // Eager load relasi

        // KUNCINYA DI SINI:
        // Jika ada stack_id yang dipilih, filter.
        // Jika TIDAK ADA (pilih All), jangan di-filter (tampilkan semua).
        if ($selectedStackId) {
            $sensorQuery->where('stack_config_id', $selectedStackId);
        }

        $sensors = $sensorQuery->get();
        $stacks = StackConfig::all();

        // --- 2. TAMBAHAN BARU: Ambil Status RCA ---
        // Kita butuh variabel ini untuk menentukan warna tombol saat halaman dimuat
        $globalConfig = GlobalConfig::first();
        $isRcaOn = $globalConfig ? $globalConfig->is_rca_mode : false;

        // --- 3. LOGIKA AJAX (Untuk Auto Refresh Data Sensor) ---
        if ($request->ajax()) {
            $realtimeData = [];

            foreach ($sensors as $sensor) {
                // Ambil data log terakhir untuk sensor ini
                $latestLog = DasLog::where('sensor_config_id', $sensor->id)
                                   ->latest('timestamp')
                                   ->first();

                $realtimeData[] = [
                    'sensor_id' => $sensor->id,
                    'sensor_code' => $sensor->sensor_code, // Penting buat selector ID unik
                    'measured'  => $latestLog ? number_format($latestLog->measured_value, 2) : '0.00',
                    'raw'       => $latestLog ? number_format($latestLog->raw_value, 2) : '0.00',
                    'status'    => $sensor->status
                ];
            }

            return response()->json($realtimeData);
        }

        // 4. Tampilan Awal (Jangan lupa kirim variable isRcaOn)
        return view('dashboard', compact('stacks', 'sensors', 'selectedStackId', 'isRcaOn'));
    }

    // --- 5. FUNGSI BARU: Toggle RCA Mode (Dipanggil saat tombol diklik) ---
    public function toggleRca()
    {
        $config = GlobalConfig::first();

        // Jaga-jaga jika data config belum ada (misal baru migrate)
        if (!$config) {
            $config = GlobalConfig::create([
                'das_unit_name' => 'Default Unit',
                'server_host' => '127.0.0.1',
                'api_endpoint' => '/api',
                'is_rca_mode' => false
            ]);
        }

        // Ubah status (True jadi False, False jadi True)
        $config->is_rca_mode = !$config->is_rca_mode;
        $config->save();

        // Kirim status baru ke Javascript agar tombol bisa berubah warna
        return response()->json([
            'status' => 'success',
            'is_rca_mode' => $config->is_rca_mode
        ]);
    }
}