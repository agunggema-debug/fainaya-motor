<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('frame_number', 50)->nullable()->after('motorcycle_type');
            $table->string('engine_number', 50)->nullable()->after('frame_number');
            $table->integer('engine_capacity')->nullable()->after('engine_number'); // Untuk CC motor
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['frame_number', 'engine_number', 'engine_capacity']);
        });
    }
};