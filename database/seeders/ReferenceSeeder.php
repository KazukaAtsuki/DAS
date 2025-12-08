<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reference;
use App\Models\SensorConfig;

class ReferenceSeeder extends Seeder
{
    public function run(): void
    {
        // Cari sensor yang namanya 'Dust', kalau tidak ada ambil random
        $sensor = SensorConfig::where('parameter_name', 'Dust')->first() ?? SensorConfig::first();

        if ($sensor) {
            Reference::create([
                'sensor_config_id' => $sensor->id,
                'range_start' => 0,
                'range_end' => 100,
                'formula' => 'x * 1.5 + 2',
            ]);
        }
    }
}
