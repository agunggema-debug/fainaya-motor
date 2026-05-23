<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique(); // Contoh: OLI-MPX2-1L
            $table->string('name');
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5); // Batas aman sebelum memicu 'Critical Alert'
            $table->decimal('purchase_price', 12, 2); // Harga modal harian gudang
            $table->decimal('sell_price', 12, 2); // Harga jual konsumen
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};