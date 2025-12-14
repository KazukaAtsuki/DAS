<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DasLog;
use App\Models\SensorConfig;
use Carbon\Carbon;

class DasLogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil SEMUA sensor yang ada di database (Termasuk Kafka, Testing, dll)
        $sensors = SensorConfig::all();

        // 2. Loop setiap sensor dan buatkan 1 data dummy terbaru
        foreach ($sensors as $sensor) {

            // Generate angka acak untuk simulasi
            $measured = rand(10, 90) + (rand(0, 99) / 100);
            $raw = $measured + rand(1, 5); // Nilai raw biasanya sedikit beda

            DasLog::create([
                'timestamp' => Carbon::now(), // Waktu SAAT INI
                'stack_config_id' => $sensor->stack_config_id, // Ambil ID Stack milik sensor tersebut
                'sensor_config_id' => $sensor->id,
                'measured_value' => $measured,
                'raw_value' => $raw,
                'status_sent_dis' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}