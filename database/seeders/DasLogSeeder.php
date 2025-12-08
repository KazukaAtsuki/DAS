<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DasLog;
use App\Models\StackConfig;
use App\Models\SensorConfig;
use Carbon\Carbon;

class DasLogSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil Stack pertama
        $stack = StackConfig::first();

        // Ambil semua sensor yang ada di stack tersebut
        $sensors = SensorConfig::where('stack_config_id', $stack->id)->get();

        if ($sensors->isEmpty()) return;

        // Buat 100 data log palsu
        for ($i = 0; $i < 100; $i++) {
            foreach ($sensors as $sensor) {
                DasLog::create([
                    'timestamp' => Carbon::now()->subMinutes($i * 5), // Mundur tiap 5 menit
                    'stack_config_id' => $stack->id,
                    'sensor_config_id' => $sensor->id,
                    'measured_value' => rand(10, 100) + (rand(0, 99) / 100), // Random float
                    'raw_value' => rand(10, 100) + (rand(0, 99) / 100),
                    'status_sent_dis' => $i % 5 == 0 ? 'Failed' : 'Sent', // Simulasi ada yang gagal
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}