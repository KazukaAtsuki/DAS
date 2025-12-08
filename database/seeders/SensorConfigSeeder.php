<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorConfig;
use App\Models\StackConfig;
use App\Models\Unit;

class SensorConfigSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID Stack dan Unit pertama (pastikan sudah di-seed sebelumnya)
        $stack = StackConfig::first();
        $unitMg = Unit::where('name', 'mg/m3')->first();
        $unitP = Unit::where('name', '%')->first();
        $unitM = Unit::where('name', 'm/sec')->first();

        // Jika data master kosong, skip seeder ini
        if (!$stack || !$unitMg) return;

        $sensors = [
            [
                'name' => 'So2', 'unit' => $unitMg->id,
                'extra' => 'Non Extra Parameter', 'corr' => 'Non Correction', 'ref' => 'No'
            ],
            [
                'name' => 'O2', 'unit' => $unitP->id ?? $unitMg->id,
                'extra' => 'O2', 'corr' => 'Non Correction', 'ref' => 'No'
            ],
            [
                'name' => 'Flowrate', 'unit' => $unitM->id ?? $unitMg->id,
                'extra' => 'Non Extra Parameter', 'corr' => 'Non Correction', 'ref' => 'No'
            ],
            [
                'name' => 'Opacity', 'unit' => $unitMg->id,
                'extra' => 'Parameter RCA', 'corr' => 'O2 Correction', 'ref' => 'No'
            ],
            [
                'name' => 'Dust', 'unit' => $unitMg->id,
                'extra' => 'Parameter RCA', 'corr' => 'O2 Correction', 'ref' => 'Yes'
            ],
        ];

        foreach ($sensors as $idx => $s) {
            SensorConfig::create([
                'sensor_code' => 'SENS-00' . ($idx + 1),
                'parameter_name' => $s['name'],
                'parameter_id' => 'PAR-' . ($idx + 1),
                'stack_config_id' => $stack->id,
                'unit_id' => $s['unit'],
                'extra_parameter' => $s['extra'],
                'o2_correction' => $s['corr'],
                'has_parameter_reference' => $s['ref'],
                'status' => 'Active',
            ]);
        }
    }
}
