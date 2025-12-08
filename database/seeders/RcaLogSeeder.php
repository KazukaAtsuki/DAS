<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RcaLog;
use App\Models\StackConfig;
use App\Models\SensorConfig;
use Carbon\Carbon;

class RcaLogSeeder extends Seeder
{
    public function run(): void
    {
        $stack = StackConfig::first();
        if (!$stack) return;

        $sensors = SensorConfig::where('stack_config_id', $stack->id)->get();

        // Buat 50 data RCA
        for ($i = 0; $i < 50; $i++) {
            foreach ($sensors as $sensor) {
                // Logic simpel: Corrected biasanya lebih besar/kecil dikit dari Measured
                $measured = rand(10, 100) + (rand(0, 99) / 100);
                $corrected = $measured * 1.1;

                RcaLog::create([
                    'timestamp' => Carbon::now()->subMinutes($i * 10), // RCA biasanya intervalnya lebih jarang
                    'stack_config_id' => $stack->id,
                    'sensor_config_id' => $sensor->id,
                    'measured_value' => $measured,
                    'corrected_o2' => $corrected,
                    'raw_value' => $measured - 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
