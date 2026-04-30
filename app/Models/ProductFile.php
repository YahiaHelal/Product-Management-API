<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFile extends Model
{
    protected $fillable = [
        'file_path',
        'file_type',
        'file_name',
        'size'
    ];

    
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
