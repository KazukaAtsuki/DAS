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
    Schema::create('sensor_configs', function (Blueprint $table) {
        $table->id();
        $table->string('sensor_code');
        $table->string('parameter_name'); // So2, O2, Flowrate, etc
        $table->string('parameter_id');   // ID untuk sinkronisasi DIS

        // Relasi ke Stack & Unit
        $table->foreignId('stack_config_id')->constrained('stack_configs')->onDelete('cascade');
        $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');

        $table->string('analyzer_ip')->nullable();
        $table->integer('port')->nullable();

        // Field Manual (Pilihan Dropdown Statis)
        $table->string('extra_parameter')->default('Non Extra Parameter');
        $table->string('o2_correction')->default('Non Correction');
        $table->string('has_parameter_reference')->default('No');

        $table->text('formula')->nullable();
        $table->string('status')->default('Active'); // Active / Inactive
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_configs');
    }
};
