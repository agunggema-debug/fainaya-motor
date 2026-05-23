<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            // item_id nullable karena baris bisa berupa Jasa Murni (misal: Setel Karbu tanpa ganti part)
            $table->foreignId('item_id')->nullable()->constrained('items')->onDelete('set null');
            $table->string('action_name'); // Nama aktivitas (Contoh: "Ganti Oli" atau "Jasa Pasang")
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2); // Snapshot harga saat transaksi terjadi
            $table->decimal('subtotal', 12, 2); // Qty x Price
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_details');
    }
};