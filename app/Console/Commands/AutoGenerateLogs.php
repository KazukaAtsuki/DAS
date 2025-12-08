<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SensorConfig;
use App\Models\DasLog;
use Carbon\Carbon;

class AutoGenerateLogs extends Command
{
    // Nama perintah nanti saat dipanggil
    protected $signature = 'logs:generate';
    protected $description = 'Inject Dummy Data secara terus menerus (Simulasi Sensor)';

    public function handle()
    {
        $this->info("🚀 Memulai simulasi sensor... (Tekan Ctrl+C untuk stop)");

        // Ambil semua sensor yang aktif
        $sensors = SensorConfig::where('status', 'Active')->get();

        if($sensors->isEmpty()) {
            $this->error("Tidak ada sensor aktif! Buat sensor dulu.");
            return;
        }

        // Looping selamanya (Infinite Loop) sampai dimatikan manual
        while(true) {
            foreach ($sensors as $sensor) {
                // Generate angka acak biar terlihat hidup
                $val = rand(10, 90) + (rand(0, 99) / 100);

                DasLog::create([
                    'timestamp' => Carbon::now(),
                    'stack_config_id' => $sensor->stack_config_id,
                    'sensor_config_id' => $sensor->id,
                    'measured_value' => $val,
                    'raw_value' => $val * 1.5, // Simulasi raw value
                    'status_sent_dis' => 'Pending',
                ]);

                $this->info("[" . Carbon::now()->format('H:i:s') . "] Data masuk: " . $sensor->parameter_name . " = " . $val);
            }

            // Jeda 2 detik sebelum looping lagi (Sesuai request senior)
            sleep(2);
        }
    }
}