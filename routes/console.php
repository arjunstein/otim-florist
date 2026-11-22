<?php

use App\Actions\GenerateProductThumbnail;
use App\Models\Product;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('products:backfill-thumbnails', function (GenerateProductThumbnail $generate) {
    $created = 0;
    $skipped = 0;

    $products = Product::query()->whereNotNull('image_path')->whereNull('thumbnail_path');
    foreach ($products->select(['id', 'image_path'])->lazyById() as $product) {
        try {
            $thumbnail = $generate($product->image_path);
        } catch (Throwable $exception) {
            report($exception);
            $thumbnail = null;
        }

        if ($thumbnail === null) {
            $skipped++;

            continue;
        }

        $updated = 0;
        try {
            $updated = (clone $products)->whereKey($product->id)
                ->where('image_path', $product->image_path)
                ->toBase()->update(['thumbnail_path' => $thumbnail]);
        } finally {
            if (! $updated) {
                Storage::disk('public')->delete($thumbnail);
            }
        }

        $created += $updated;
        $skipped += 1 - $updated;
    }

    $this->info("Thumbnails created: {$created}. Skipped: {$skipped}.");
})->purpose('Generate thumbnails for existing product images');
