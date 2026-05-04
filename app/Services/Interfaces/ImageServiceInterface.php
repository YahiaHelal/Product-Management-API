<?php

namespace App\Services\Interfaces;

use Illuminate\Http\UploadedFile;

interface ImageServiceInterface {

    public function upload(UploadedFile $image, string $dir = 'images'): string;

    public function uploadMultiple(array $images, string $dir = 'images'): array;

    public function delete(?string $path): bool;

    public function deleteMultiple(array $paths): void;

    public function replace(?string $oldPath, UploadedFile $newImage, string $dir = 'images'): string;

    public function url(?string $path): ?string;
}
