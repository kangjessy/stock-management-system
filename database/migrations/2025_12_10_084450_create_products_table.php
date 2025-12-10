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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // Untuk path gambar
            
            $table->float('price_in', 10, 2); // Harga Beli
            $table->float('price_out', 10, 2); // Harga Jual
            $table->float('reorder_point')->default(0); // Batas stok minimum
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
