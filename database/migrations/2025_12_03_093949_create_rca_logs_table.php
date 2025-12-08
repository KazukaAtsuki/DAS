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
    Schema::create('rca_logs', function (Blueprint $table) {
        $table->id();
        $table->timestamp('timestamp');

        // Relasi ke Stack & Sensor
        $table->foreignId('stack_config_id')->constrained('stack_configs')->onDelete('cascade');
        $table->foreignId('sensor_config_id')->constrained('sensor_configs')->onDelete('cascade');

        $table->float('measured_value');  // Nilai Terukur
        $table->float('corrected_o2');    // Nilai Terkoreksi O2 (Khas RCA)
        $table->float('raw_value');       // Nilai Mentah

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rca_logs');
    }
};
