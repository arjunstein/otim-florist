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

    protected const CACHE_KEYS = [
        "products-20",
        "landingpage-html-20",
        "products-promo-20",
        "products-bunga-papan-20",
        "products-bunga-meja-20",
        "products-bunga-papan-congratulation-20",
        "products-bunga-papan-dukacita-20",
        "products-bunga-papan-happy-wedding-20",
        "products-bunga-papan-selamat-20",
        "products-bunga-salib-20",
        "products-bunga-standing-20",
        "products-hand-bouquet-20",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function () {
            self::clearCache();
        });

        static::updating(function ($model) {
            if ($model->isDirty('image') && ($model->getOriginal('image') !== null)) {
                Storage::disk('public')->delete($model->getOriginal('image'));
            }
            self::clearCache();
        });
    }

    protected static function clearCache()
    {
        foreach (self::CACHE_KEYS as $key) {
            Cache::forget($key);
        }
    }
}
