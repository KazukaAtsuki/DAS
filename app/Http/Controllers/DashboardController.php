<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StackConfig;
use App\Models\SensorConfig;
use App\Models\DasLog;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Stack yang dipilih (dari URL ?stack_id=1)
        // Jika tidak ada, ambil stack pertama
        $selectedStackId = $request->query('stack_id');

        if (!$selectedStackId) {
            $firstStack = StackConfig::first();
            $selectedStackId = $firstStack ? $firstStack->id : null;
        }

        $stacks = StackConfig::all();

        // 2. Ambil Sensor berdasarkan Stack yang dipilih
        $sensors = SensorConfig::where('stack_config_id', $selectedStackId)->get();

        // 3. LOGIKA AJAX (Ini yang bikin auto refresh)
        if ($request->ajax()) {
            $realtimeData = [];

            foreach ($sensors as $sensor) {
                // Ambil data log terakhir untuk sensor ini
                $latestLog = DasLog::where('sensor_config_id', $sensor->id)
                                   ->latest('timestamp')
                                   ->first();

                $realtimeData[] = [
                    'sensor_id' => $sensor->id,
                    'measured'  => $latestLog ? number_format($latestLog->measured_value, 2) : '0.00',
                    'raw'       => $latestLog ? number_format($latestLog->raw_value, 2) : '0.00',
                    'status'    => $sensor->status // Active/Inactive
                ];
            }

            return response()->json($realtimeData);
        }

        // 4. Tampilan Awal (Load Biasa)
        return view('dashboard', compact('stacks', 'sensors', 'selectedStackId'));
    }
}