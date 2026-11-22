<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateProductThumbnail
{
    public function __invoke(string $imagePath): ?string
    {
        if (! function_exists('imagewebp') || ! (imagetypes() & IMG_WEBP)) {
            return null;
        }

        $disk = Storage::disk('public');
        $contents = $disk->get($imagePath);
        $dimensions = $contents ? @getimagesizefromstring($contents) : false;

        if (! $dimensions || $dimensions[0] * $dimensions[1] > 12_000_000) {
            return null;
        }

        $source = @imagecreatefromstring($contents);

        if ($source === false) {
            return null;
        }

        $scale = min(1, 480 / max($dimensions[0], $dimensions[1]));
        $thumbnail = imagescale($source, max(1, (int) round($dimensions[0] * $scale)), max(1, (int) round($dimensions[1] * $scale)));

        $file = tmpfile();
        if ($thumbnail === false || $file === false) {
            if ($file !== false) {
                fclose($file);
            }

            return null;
        }

        try {
            if (! imagewebp($thumbnail, $file, 80) || ftell($file) === 0) {
                return null;
            }

            rewind($file);
            $path = 'products/thumbnails/'.Str::uuid().'.webp';

            return $disk->put($path, $file) ? $path : null;
        } finally {
            fclose($file);
        }
    }
}
