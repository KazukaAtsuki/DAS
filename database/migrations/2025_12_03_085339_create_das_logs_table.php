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
        Schema::create('das_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('timestamp'); // Waktu data masuk

            // Relasi ke Stack & Sensor
            $table->foreignId('stack_config_id')->constrained('stack_configs')->onDelete('cascade');
            $table->foreignId('sensor_config_id')->constrained('sensor_configs')->onDelete('cascade');

            $table->float('measured_value'); // Nilai terukur
            $table->float('raw_value');      // Nilai mentah
            $table->enum('status_sent_dis', ['Sent', 'Pending', 'Failed'])->default('Pending'); // Status kirim ke server KLHK/DIS

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('das_logs');
    }
};
