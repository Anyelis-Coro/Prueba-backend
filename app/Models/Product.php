<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency_id',
        'tax_cost',
        'manufacturing_cost',
    ];

    // Obliga a Laravel a tratar estos campos estrictamente como números decimales
    protected $casts = [
        'price' => 'decimal:2',
        'tax_cost' => 'decimal:2',
        'manufacturing_cost' => 'decimal:2',
    ];

    // Relación: Un producto pertenece a una divisa base
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }
}
