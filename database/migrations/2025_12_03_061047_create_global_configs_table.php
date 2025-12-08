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
        Schema::create('global_configs', function (Blueprint $table) {
            $table->id();
            $table->string('das_unit_name');        // Nama DAS
            $table->string('server_host');          // IP Server
            $table->string('api_endpoint');         // URL Endpoint
            $table->string('server_api_key')->nullable(); // Password/Key (Boleh null)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_configs');
    }
};
