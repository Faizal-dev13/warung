<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class ImageOptimizationService
{
    public function storeWebp(UploadedFile $file, string $directory, int $maxWidth = 1400, int $quality = 82): string
    {
        if (! function_exists('imagewebp')) {
            return $file->store($directory, 'public');
        }

        try {
            $source = $this->createImageResource($file);
            if (! $source) {
                return $file->store($directory, 'public');
            }

            $width = imagesx($source);
            $height = imagesy($source);
            $targetWidth = min($width, $maxWidth);
            $targetHeight = (int) round($height * ($targetWidth / max(1, $width)));

            $canvas = imagecreatetruecolor($targetWidth, $targetHeight);
            imagepalettetotruecolor($canvas);
            imagealphablending($canvas, true);
            imagesavealpha($canvas, true);

            imagecopyresampled($canvas, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

            $relativePath = trim($directory, '/').'/'.Str::uuid().'.webp';
            $absolutePath = Storage::disk('public')->path($relativePath);
            File::ensureDirectoryExists(dirname($absolutePath));

            if (! imagewebp($canvas, $absolutePath, $quality)) {
                imagedestroy($source);
                imagedestroy($canvas);

                return $file->store($directory, 'public');
            }

            imagedestroy($source);
            imagedestroy($canvas);

            return $relativePath;
        } catch (Throwable) {
            return $file->store($directory, 'public');
        }
    }

    private function createImageResource(UploadedFile $file): mixed
    {
        $mime = $file->getMimeType();
        $path = $file->getRealPath();

        return match ($mime) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($path) : null,
            default => null,
        };
    }
}
