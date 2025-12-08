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
    Schema::create('references', function (Blueprint $table) {
        $table->id();
        // Relasi ke Sensor (karena Sensor punya info Stack & Parameter Name seperti "Dust")
        $table->foreignId('sensor_config_id')->constrained('sensor_configs')->onDelete('cascade');

        $table->float('range_start');
        $table->float('range_end');
        $table->text('formula');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
