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
    Schema::table('sensor_configs', function (Blueprint $table) {
        // Default 0 atau null (artinya tidak ada batas)
        $table->float('limit_value')->nullable()->default(100)->after('unit_id');
    });
}

public function down(): void
{
    Schema::table('sensor_configs', function (Blueprint $table) {
        $table->dropColumn('limit_value');
    });
}
};
