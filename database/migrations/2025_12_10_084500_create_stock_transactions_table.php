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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict'); // Siapa yang melakukan transaksi
            
            // Jenis Transaksi: IN (Penerimaan), OUT (Penjualan/Kerusakan), ADJ (Penyesuaian), TRF (Transfer)
            $table->enum('type', ['IN', 'OUT', 'ADJ', 'TRF']); 
            $table->integer('quantity'); // Jumlah perubahan stok (bisa positif atau negatif)
            $table->string('reference_id')->nullable(); // ID referensi (misalnya ID PO, ID SO, atau ID Adjustment)
            $table->text('description')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
