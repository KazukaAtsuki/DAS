<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlobalConfigSeeder extends Seeder
{
    public function run(): void
    {
        // Cek dulu biar gak dobel, kalau kosong baru isi
        if (DB::table('global_configs')->count() == 0) {
            DB::table('global_configs')->insert([
                'das_unit_name' => 'Trusur DAS V3',
                'server_host' => '127.0.0.1',
                'api_endpoint' => 'http://127.0.0.1/api/val',
                'server_api_key' => 'rahasia123',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

