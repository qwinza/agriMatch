<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // Kode pesanan unik
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pembeli
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Produk yang dipesan
            $table->integer('quantity'); // Jumlah pesanan
            $table->decimal('total_price', 15, 2); // Total harga
            $table->text('shipping_address'); // Alamat pengiriman
            $table->text('notes')->nullable(); // Catatan pesanan
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('status_updated_at')->nullable(); // Waktu status terupdate
            $table->timestamps();

            // Index untuk performa query
            $table->index('order_code');
            $table->index('status');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};