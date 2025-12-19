<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StackConfig;
use App\Models\SensorConfig;
use App\Models\DasLog;
use App\Models\RcaLog; // Pastikan ini ada
use App\Models\GlobalConfig;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter Stack & Config
        $selectedStackId = $request->query('stack_id');
        $globalConfig = GlobalConfig::first();
        $isRcaOn = $globalConfig ? $globalConfig->is_rca_mode : false;

        $sensorQuery = SensorConfig::with(['unit', 'stackConfig']);
        if ($selectedStackId) {
            $sensorQuery->where('stack_config_id', $selectedStackId);
        }
        $sensors = $sensorQuery->get();
        $stacks = StackConfig::all();

        // 2. LOGIKA AJAX (MODIFIKASI UNTUK CHART)
        if ($request->ajax()) {
            $realtimeData = [];

            foreach ($sensors as $sensor) {
                // Tentukan tabel mana yang dipakai (RCA atau DAS Biasa)
                $model = $isRcaOn ? RcaLog::class : DasLog::class;

                // A. Ambil 1 data terakhir untuk Angka Besar (Display Utama)
                $latestLog = $model::where('sensor_config_id', $sensor->id)
                                   ->orderBy('id', 'desc')
                                   ->first();

                // B. Ambil 20 data terakhir untuk Grafik (Chart History)
                // Kita ambil descending (terbaru), lalu sort ascending (lama ke baru) agar grafik jalan ke kanan
                $historyLogs = $model::where('sensor_config_id', $sensor->id)
                                    ->orderBy('id', 'desc')
                                    ->take(20) // Ambil 20 titik data
                                    ->get()
                                    ->sortBy('id') // Balik urutan agar kronologis
                                    ->pluck('measured_value') // Hanya ambil nilainya
                                    ->values()
                                    ->toArray();

                // Jika data kosong (sensor baru), isi dengan array 0 biar grafik gak error
                if (empty($historyLogs)) {
                    $historyLogs = array_fill(0, 20, 0);
                }

                $realtimeData[] = [
                    'sensor_id' => $sensor->id,
                    'measured'  => $latestLog ? number_format($latestLog->measured_value, 2) : '0.00',
                    'raw'       => $latestLog ? number_format($latestLog->raw_value, 2) : '0.00',
                    // Kirim data array untuk grafik
                    'chart_data' => $historyLogs
                ];
            }

            return response()->json($realtimeData)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        return view('dashboard', compact('stacks', 'sensors', 'selectedStackId', 'isRcaOn'));
    }

    public function toggleRca()
    {
        $config = GlobalConfig::firstOrNew();
        $config->is_rca_mode = !$config->is_rca_mode;
        $config->save();

        return response()->json([
            'status' => 'success',
            'is_rca_mode' => $config->is_rca_mode
        ]);
    }
}