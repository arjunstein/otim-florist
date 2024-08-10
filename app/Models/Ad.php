<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Ad extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function () {
            self::clearAdCache();
        });

        static::updating(function ($model) {
            if ($model->isDirty('image') && $model->getOriginal('image') !== null) {
                Storage::disk('public')->delete($model->getOriginal('image'));
            }
            self::clearAdCache();
        });
    }

    private static function clearAdCache()
    {
        $keys = [
            "ads",
            "ads-promo",
            "landingpage-html-20",
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
