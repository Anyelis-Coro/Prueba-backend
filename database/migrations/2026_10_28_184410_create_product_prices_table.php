<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id(); // Identificador único[cite: 2]
            
            // Llaves foráneas hacia productos y divisas[cite: 2]
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('restrict');
            $table->unique(['product_id', 'currency_id']);
            $table->decimal('price', 10, 2); // Precio en la divisa especificada[cite: 2]
            
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
