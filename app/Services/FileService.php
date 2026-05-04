<?php
namespace App\Services;

use App\Services\Interfaces\FileServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Override;

class FileService implements FileServiceInterface {

    public function upload(UploadedFile $file, string $dir = 'files'): array
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;

        $path = $file->storeAs($dir, $fileName, 'public');
        return [
            'file_path' => $path,
            'file_name' => $originalName,
            'file_type' => $extension,
            'file_size' => $file->getSize()
        ];
    }

    public function uploadMultiple(array $files, $dir = 'files'): array
    {
        $fileInfo = [];
        foreach($files as $file) {
            $fileInfo[] = $this->upload($file, $dir);
        }
        return $fileInfo;
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

    public function url(?string $path): ?string
    {
        if(!$path) {
            return null;
        }
        return Storage::disk('public')->url($path);
    }

}
