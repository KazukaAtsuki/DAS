<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HourlyLog;
use App\Models\StackConfig;
use App\Models\SensorConfig;
use Carbon\Carbon;

class HourlyLogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil SEMUA Stack (termasuk 'kafka')
        $stacks = StackConfig::all();

        foreach ($stacks as $stack) {
            // Ambil sensor milik stack ini
            $sensors = SensorConfig::where('stack_config_id', $stack->id)->get();

            if ($sensors->isEmpty()) continue;

            // 2. Generate data 24 jam terakhir
            for ($i = 0; $i < 24; $i++) {
                $time = Carbon::now()->subHours($i)->startOfHour(); // Jam bulat (08:00, 07:00, dst)

                foreach ($sensors as $sensor) {
                    $measured = rand(20, 80) + (rand(0, 99) / 100);

                    HourlyLog::create([
                        'timestamp' => $time,
                        'stack_config_id' => $stack->id,
                        'sensor_config_id' => $sensor->id,
                        'measured_value' => $measured,
                        'corrected_value' => $measured * 1.05, // Simulasi koreksi sedikit lebih tinggi
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
