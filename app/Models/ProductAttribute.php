<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'value',
    ];

    protected $casts = [
        'product_id' => 'integer',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeByName(Builder $query, string $name)
    {
        return $query->where('name', $name);
    }


    public function scopeByValue(Builder $query, string $value)
    {
        return $query->where('value', $value);
    }

    public function scopeForProduct(Builder $query, int $productId)
    {
        return $query->where('product_id', $productId);
    }
}
