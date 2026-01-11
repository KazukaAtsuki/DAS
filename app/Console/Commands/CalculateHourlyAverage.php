<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SensorConfig;
use App\Models\DasLog;
use App\Models\HourlyLog;
use Carbon\Carbon;

class CalculateHourlyAverage extends Command
{
    protected $signature = 'das:calc-hourly';
    protected $description = 'Menghitung rata-rata data logs per jam';

    public function handle()
    {
        $this->info('🚀 Memulai perhitungan Hourly Average (WIB)...');

        // KITA HITUNG DATA JAM INI (Biar langsung kelihatan hasilnya)
        $start = Carbon::now()->startOfHour(); // Misal 15:00
        $end   = Carbon::now()->endOfHour();   // Misal 15:59

        $this->info("📅 Menghitung data: " . $start->toDateTimeString() . " s/d " . $end->toDateTimeString());

        $sensors = SensorConfig::where('status', 'Active')->get();

        foreach ($sensors as $sensor) {
            // Ambil rata-rata
            $average = DasLog::where('sensor_config_id', $sensor->id)
                             ->whereBetween('timestamp', [$start, $end])
                             ->avg('measured_value');

            if (is_null($average)) {
                $this->warn("- Sensor: {$sensor->parameter_name} (Belum ada data)");
                continue;
            }

            // Simpan ke hourly_logs (Update jika sudah ada, Buat baru jika belum)
            HourlyLog::updateOrCreate(
                [
                    'timestamp' => $start,
                    'sensor_config_id' => $sensor->id,
                    'stack_config_id' => $sensor->stack_config_id,
                ],
                [
                    'measured_value' => $average,
                    'corrected_value' => $average * 1.05, // Simulasi koreksi
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $this->info("✅ Saved: {$sensor->parameter_name} | Avg: " . number_format($average, 2));
        }

        $this->info('🎉 Selesai!');
    }
}