<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Identificador único
            $table->string('name'); // Nombre del producto[cite: 1]
            $table->string('description'); // Descripción del producto[cite: 1]
            $table->decimal('price', 10, 2); // Precio base[cite: 1]
            
            // Identificador de la divisa base (Llave foránea)[cite: 1]
            $table->foreignId('currency_id')
                  ->constrained('currencies')
                  ->onDelete('restrict'); // Evita borrar una divisa si tiene productos asociados
            
            $table->decimal('tax_cost', 10, 2); // Costo de impuestos[cite: 1]
            $table->decimal('manufacturing_cost', 10, 2); // Costo de fabricación[cite: 1]
            
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
