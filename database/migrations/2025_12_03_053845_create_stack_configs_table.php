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
    Schema::create('stack_configs', function (Blueprint $table) {
        $table->id();
        $table->string('stack_name'); // Nama Stack (Contoh: Stack LK)
        $table->decimal('oxygen_reference', 5, 2)->nullable(); // Persen Oksigen (bisa koma, bisa null)
        $table->enum('status', ['Active', 'Inactive'])->default('Active'); // Status
        $table->timestamps(); // Created at & Last Updated
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stack_configs');
    }
};
