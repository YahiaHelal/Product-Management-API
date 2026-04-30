<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, Translatable;

    // Non-translatable attributes
    protected $fillable = [
        'sku',
        'price',
        'sale_price',
        'stock',
        'brand',
        'main_image_path',
        'status',
        'category_id',
    ];

    public $translatedAttributes = [
        'title',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'status' => 'boolean',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function files()
    {
        return $this->hasMany(ProductFile::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')
                    ->withTimestamps();
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', true);
    }

    public function scopeInactive(Builder $query)
    {
        return $query->where('status', false);
    }

    public function scopeInStock(Builder $query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOutOfStock(Builder $query)
    {
        return $query->where('stock', '=', 0);
    }

    public function scopeByBrand(Builder $query, string $brand)
    {
        return $query->where('brand', 'LIKE', "%{$brand}%");
    }

    public function scopeByCategory(Builder $query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopePriceRange(Builder $query, ?float $minPrice = null, ?float $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->whereTranslationLike('title', "%{$search}%");
    }

    public function isOnSale(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }


    public function getEffectivePrice(): float
    {
        return $this->isOnSale() ? $this->sale_price : $this->price;
    }

    public function getDiscountPercentage(): ?int
    {
        if (!$this->isOnSale()) {
            return null;
        }

        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
