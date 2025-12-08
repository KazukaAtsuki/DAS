<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StackConfigSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            DB::table('stack_configs')->insert([
                'stack_name' => 'Stack LK - ' . $i,
                'oxygen_reference' => rand(10, 21), // Random angka
                'status' => $i % 2 == 0 ? 'Active' : 'Inactive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
