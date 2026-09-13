<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AvatarService
{
    /**
     * Maximum dimension (width or height) in pixels.
     */
    public const MAX_DIMENSION = 500;

    /**
     * JPEG compression quality (0-100).
     */
    public const COMPRESSION_QUALITY = 82;

    /**
     * Upload, resize, compress, and save avatar to disk.
     * Returns the relative path in the public storage disk: 'avatars/{filename}.jpg'
     *
     * @param UploadedFile $file
     * @param string|null $oldPath
     * @return string
     */
    public function uploadAndCompress(UploadedFile $file, ?string $oldPath = null): string
    {
        // 1. Generate unique filename and disk path
        $filename = 'avatar_' . Str::random(20) . '_' . time() . '.jpg';
        $storagePath = 'avatars/' . $filename;

        // 2. Load image using GD based on mime type
        $mime = $file->getMimeType();
        $sourcePath = $file->getRealPath();

        $sourceImage = null;
        if (in_array($mime, ['image/jpeg', 'image/jpg', 'image/pjpeg'], true)) {
            $sourceImage = @imagecreatefromjpeg($sourcePath);
        } elseif ($mime === 'image/png') {
            $sourceImage = @imagecreatefrompng($sourcePath);
        } else {
            // Fallback load string
            $contents = @file_get_contents($sourcePath);
            if ($contents) {
                $sourceImage = @imagecreatefromstring($contents);
            }
        }

        if (!$sourceImage) {
            // Fallback if GD create fails: store file directly
            $storedPath = $file->storeAs('avatars', $filename, 'public');
            $this->delete($oldPath);
            return $storedPath;
        }

        // 3. Calculate dimensions preserving aspect ratio with max 500x500
        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        $newWidth = $origWidth;
        $newHeight = $origHeight;

        if ($origWidth > self::MAX_DIMENSION || $origHeight > self::MAX_DIMENSION) {
            if ($origWidth >= $origHeight) {
                $newWidth = self::MAX_DIMENSION;
                $newHeight = (int) round(($origHeight / $origWidth) * self::MAX_DIMENSION);
            } else {
                $newHeight = self::MAX_DIMENSION;
                $newWidth = (int) round(($origWidth / $origHeight) * self::MAX_DIMENSION);
            }
        }

        // 4. Create resized truecolor image with white background (handles transparent PNGs gracefully)
        $targetImage = imagecreatetruecolor($newWidth, $newHeight);
        $white = imagecolorallocate($targetImage, 255, 255, 255);
        imagefill($targetImage, 0, 0, $white);

        // Resample with high quality interpolation
        imagecopyresampled(
            $targetImage,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth,
            $newHeight,
            $origWidth,
            $origHeight
        );

        // 5. Compress and capture JPEG stream
        ob_start();
        imagejpeg($targetImage, null, self::COMPRESSION_QUALITY);
        $compressedData = ob_get_clean();

        // 6. Save through Storage public disk
        Storage::disk('public')->put($storagePath, $compressedData);

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($targetImage);

        // 7. Delete previous avatar if exists
        $this->delete($oldPath);

        return $storagePath;
    }

    /**
     * Delete an existing avatar from disk.
     *
     * @param string|null $path
     * @return void
     */
    public function delete(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        // Do not delete external URLs
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return;
        }

        // Strip leading 'storage/' or '/storage/' or '/'
        $cleanPath = ltrim($path, '/');
        if (Str::startsWith($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        if (Storage::disk('public')->exists($cleanPath)) {
            Storage::disk('public')->delete($cleanPath);
        }
    }
}
