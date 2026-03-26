<?php

namespace Taki47\Gallery\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    public function upload(UploadedFile $file, string $directory, string $disk)
    {
        $filename = $this->getFileName($file, $directory, $disk);

        $path = $file->storeAs($directory, $filename, $disk);
        
        return $path;
    }

    public function delete(string $file, string $disk): bool
    {
        return Storage::disk($disk)->delete($file);
    }

    private function getFileName(UploadedFile $file, string $directory, string $disk): string
    {
        // fájl eredeti neve
        $originalName = $file->getClientOriginalName();

        // fájlnév szétbontása kiterjesztésre
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        // teljes slug-osított fájlnév
        $baseName = Str::slug($name);
        $fileName = $baseName . '.' . $extension;
        
        $counter = 1;
        while (Storage::disk($disk)->exists($directory . "/" . $fileName)) {
            $fileName = $baseName . '-' . $counter . '.' . $extension;
            $counter++;
        }
        return $fileName;
    }
}