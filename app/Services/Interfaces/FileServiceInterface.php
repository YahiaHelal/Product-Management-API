<?php
namespace App\Services\Interfaces;

use Illuminate\Http\UploadedFile;

interface FileServiceInterface {

    public function upload(UploadedFile $file, string $dir = 'files'): array;

    public function uploadMultiple(array $files, $dir = 'files'): array;

    public function delete(?string $path): bool;

    public function deleteMultiple(array $paths): void;

    public function url(?string $path): ?string;
}
