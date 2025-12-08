<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HourlyAverage;
use App\Models\StackConfig;
use App\Models\SensorConfig;
use Carbon\Carbon;

class HourlyAverageSeeder extends Seeder
{
    public function run(): void
    {
        $stack = StackConfig::first();
        if (!$stack) return;

        $sensors = SensorConfig::where('stack_config_id', $stack->id)->get();

        // Loop mundur 48 jam ke belakang
        for ($i = 0; $i < 48; $i++) {
            $time = Carbon::now()->subHours($i)->startOfHour(); // Paksa menit 00:00

            foreach ($sensors as $sensor) {
                // Logic simulasi: Nilai rata-rata biasanya stabil
                $measured = rand(20, 80) + (rand(0, 99) / 100);

                HourlyAverage::create([
                    'timestamp' => $time,
                    'stack_config_id' => $stack->id,
                    'sensor_config_id' => $sensor->id,
                    'measured_value' => $measured,
                    'corrected_value' => $measured * 1.05, // Simulasi koreksi O2
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
