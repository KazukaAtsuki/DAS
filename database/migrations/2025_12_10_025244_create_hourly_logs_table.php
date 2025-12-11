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
    Schema::create('hourly_logs', function (Blueprint $table) {
        $table->id();
        $table->timestamp('timestamp'); // Misal: 2025-12-10 08:00:00 (Pas di jam 00)

        // Relasi
        $table->foreignId('stack_config_id')->constrained('stack_configs')->onDelete('cascade');
        $table->foreignId('sensor_config_id')->constrained('sensor_configs')->onDelete('cascade');

        $table->float('measured_value');   // Rata-rata nilai terukur
        $table->float('corrected_value');  // Rata-rata nilai terkoreksi

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hourly_logs');
    }
};
