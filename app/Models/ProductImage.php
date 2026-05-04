<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
    ];

    protected $appends = ['image_url'];

    protected function imageUrl(): Attribute {
        return Attribute::make(
            get: fn () => $this->image_path ? Storage::disk('public')->url($this->image_path) : null
        );
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
