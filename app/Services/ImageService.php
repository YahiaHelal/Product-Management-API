<?php

namespace App\Services;

use App\Services\Interfaces\ImageServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService implements ImageServiceInterface {

    // upload image and reutrn it's path
    public function upload(UploadedFile $image, string $dir = 'images'): string
    {
        $originalName = Str::uuid();
        $extension =  $image->getClientOriginalExtension();
        $fileName = $originalName . '.' . $extension;

        $path = $image->storeAs($dir, $fileName, 'public'); // stored in storage/app/public [inaccessable]
        return $path;
    }

    public function uploadMultiple(array $images, string $dir = 'images'): array
    {
        $paths = [];

        foreach($images as $image) {
            $paths[] = $this->upload($image, $dir);
        }
        return $paths;
    }

    public function delete(?string $path): bool
    {
        if(!$path || !Storage::disk('public')->exists($path)) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }

    public function deleteMultiple(array $paths): void
    {
        foreach($paths as $path) {
            $this->delete($path);
        }
    }

    public function replace(?string $oldPath, UploadedFile $newImage, string $dir = 'images'): string
    {
        // upload new image then delete the old image

        $newPath = $this->upload($newImage, $dir);
        if($oldPath) {
            $this->delete($oldPath);
        }
        return $newPath;
    }

    public function url(?string $path): ?string
    {
        if(!$path) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

}
