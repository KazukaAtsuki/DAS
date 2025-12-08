<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            'kg/h', 'C', 'm/sec', '%', 'ton',
            'minutes', 'Nm3/h', 'm3/h', 'm3/min', 'l/min'
        ];

        foreach ($units as $unit) {
            DB::table('units')->insert([
                'name' => $unit,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
