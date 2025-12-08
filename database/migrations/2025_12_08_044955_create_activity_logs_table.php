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
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        // Siapa yang melakukan?
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        // Apa jenis aksinya? (CREATE, UPDATE, DELETE, LOGIN)
        $table->string('action');
        // Detailnya apa? (Misal: "Menambah Sensor Baru")
        $table->string('description');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
