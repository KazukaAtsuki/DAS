<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('hourly_averages', function (Blueprint $table) {
        $table->id();
        $table->timestamp('timestamp'); // Waktu (misal: 08:00, 09:00)

        // Relasi
        $table->foreignId('stack_config_id')->constrained('stack_configs')->onDelete('cascade');
        $table->foreignId('sensor_config_id')->constrained('sensor_configs')->onDelete('cascade');

        $table->float('measured_value');   // Rata-rata Nilai Terukur
        $table->float('corrected_value');  // Rata-rata Nilai Terkoreksi (O2)

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hourly_averages');
    }
};
