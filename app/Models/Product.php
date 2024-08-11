<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function () {
            Cache::forget("products-20"); // delete cache landing page product
            Cache::forget("landingpage-html-20"); // delete cache landing page product
            Cache::forget("products-promo-20"); // delete cache promopage
            Cache::forget("products-bunga-papan-20"); // delete cache bunga papan
            Cache::forget("products-bunga-meja-20"); // delete cache bunga meja
            Cache::forget("products-bunga-papan-congratulation-20"); // delete cache bunga papan congrats
            Cache::forget("products-bunga-papan-dukacita-20"); // delete cache bunga papan duka
            Cache::forget("products-bunga-papan-happy-wedding-20"); // delete cache bunga papan wedding
            Cache::forget("products-bunga-papan-selamat-20"); // delete cache bunga papan selamat
            Cache::forget("products-bunga-salib-20"); // delete cache bunga salib
            Cache::forget("products-bunga-standing-20"); // delete cache bunga standing
            Cache::forget("products-hand-bouquet-20"); // delete cache hand bouquet
            Cache::forget("categories_with_count"); // delete cache product detail
        });

        static::updating(function ($model) {
            if ($model->isDirty('image') && ($model->getOriginal('image') !== null)) {
                Storage::disk('public')->delete($model->getOriginal('image'));
            }

            Cache::forget("products-20"); // delete cache landing page product
            Cache::forget("landingpage-html-20"); // delete cache landing page product
            Cache::forget("products-promo-20"); // delete cache promopage
            Cache::forget("products-bunga-papan-20"); // delete cache bunga papan
            Cache::forget("products-bunga-meja-20"); // delete cache bunga meja
            Cache::forget("products-bunga-papan-congratulation-20"); // delete cache bunga papan congrats
            Cache::forget("products-bunga-papan-dukacita-20"); // delete cache bunga papan duka
            Cache::forget("products-bunga-papan-happy-wedding-20"); // delete cache bunga papan wedding
            Cache::forget("products-bunga-papan-selamat-20"); // delete cache bunga papan selamat
            Cache::forget("products-bunga-salib-20"); // delete cache bunga salib
            Cache::forget("products-bunga-standing-20"); // delete cache bunga standing
            Cache::forget("products-hand-bouquet-20"); // delete cache hand bouquet
            Cache::forget("categories_with_count"); // delete cache product detail
        });
    }
}
