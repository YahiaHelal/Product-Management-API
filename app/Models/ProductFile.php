<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductFile extends Model
{
    protected $fillable = [
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    protected $appends = ['file_url', 'formatted_file_size'];

    protected function fileUrl(): Attribute {
        return Attribute::make(
            get: fn () => $this->file_path ? Storage::disk('public')->url($this->file_path) : null
        );
    }

    protected function formattedFileSize(): Attribute {
        return Attribute::make(
            get: function () {
                $bytes = $this->file_size;
                $units = ['B', 'KB', 'MB', 'GB'];

                for($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                    $bytes /= 1024;
                }

                return round($bytes, 2) . ' ' . $units[$i];
            }
        );
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
