<?php

namespace Taki47\Gallery\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * --------------------------------------------------------------------------
 * Laravel Gallery - Image Upload Service
 * --------------------------------------------------------------------------
 *
 * Handles image file operations for the gallery package.
 *
 * Responsibilities:
 * - Upload images to the configured storage disk
 * - Generate SEO-friendly unique filenames
 * - Delete images from storage
 *
 * The service ensures that uploaded files:
 * - keep a readable slugified name
 * - never overwrite existing files
 * - are stored inside the configured gallery directory
 *
 * Package: taki47/laravel-gallery
 * Author:  Lajos Takács
 */
class ImageUploadService
{
    /**
     * Upload an image to the given directory on the specified disk.
     *
     * Steps performed:
     * 1. Generate a unique slugified filename
     * 2. Store the file using Laravel's filesystem
     * 3. Return the stored file path
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return string
     */
    public function upload(UploadedFile $file, string $directory, string $disk)
    {
        /**
         * Generate a unique filename for the uploaded file.
         */
        $filename = $this->getFileName($file, $directory, $disk);

        /**
         * Store the file in the specified directory and disk.
         */
        $path = $file->storeAs($directory, $filename, $disk);
        
        /**
         * Return the stored file path.
         */
        return $path;
    }

    /**
     * Delete an image file from the configured storage disk.
     *
     * @param string $file
     * @param string $disk
     * @return bool
     */
    public function delete(string $file, string $disk): bool
    {
        /**
         * Remove the file from the storage disk.
         */
        return Storage::disk($disk)->delete($file);
    }

    /**
     * Generate a unique filename for the uploaded image.
     *
     * The filename is created by:
     * - extracting the original filename
     * - converting it to a slug
     * - appending a counter if the file already exists
     *
     * Example:
     * original: "My Image.jpg"
     * stored:   "my-image.jpg"
     * conflict: "my-image-1.jpg"
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return string
     */
    private function getFileName(UploadedFile $file, string $directory, string $disk): string
    {
        /**
         * Retrieve the original filename.
         */
        $originalName = $file->getClientOriginalName();

        /**
         * Split filename into name and extension.
         */
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        /**
         * Generate slugified base filename.
         */
        $baseName = Str::slug($name);

        /**
         * Create initial filename.
         */
        $fileName = $baseName . '.' . $extension;
        
        /**
         * Ensure filename uniqueness by appending a counter if needed.
         */
        $counter = 1;
        while (Storage::disk($disk)->exists($directory . "/" . $fileName)) {
            $fileName = $baseName . '-' . $counter . '.' . $extension;
            $counter++;
        }

        return $fileName;
    }
}