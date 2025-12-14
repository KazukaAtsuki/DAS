<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StackConfig;
use App\Models\SensorConfig;
use App\Models\DasLog;
use App\Models\GlobalConfig;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter Stack
        $selectedStackId = $request->query('stack_id');
        $sensorQuery = SensorConfig::with(['unit', 'stackConfig']);

        if ($selectedStackId) {
            $sensorQuery->where('stack_config_id', $selectedStackId);
        }

        $sensors = $sensorQuery->get();
        $stacks = StackConfig::all();

        // 2. Cek Status RCA
        $globalConfig = GlobalConfig::first();
        $isRcaOn = $globalConfig ? $globalConfig->is_rca_mode : false;

        // 3. LOGIKA AJAX (YANG DIPERBAIKI)
        if ($request->ajax()) {
            $realtimeData = [];

            foreach ($sensors as $sensor) {
                // PERBAIKAN DI SINI:
                // Ganti latest('timestamp') menjadi latest('id')
                // Ini memastikan kita mengambil data yang BARU SAJA DIINPUT
                $latestLog = DasLog::where('sensor_config_id', $sensor->id)
                                   ->orderBy('id', 'desc') // Ambil ID paling besar
                                   ->first();

                $realtimeData[] = [
                    'sensor_id' => $sensor->id,
                    'measured'  => $latestLog ? number_format($latestLog->measured_value, 2) : '0.00',
                    'raw'       => $latestLog ? number_format($latestLog->raw_value, 2) : '0.00',
                    // Debugging: Kita kirim ID log-nya juga biar tau ini data baru/lama
                    'log_id'    => $latestLog ? $latestLog->id : 0
                ];
            }

            // Tambahkan header agar tidak di-cache browser
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