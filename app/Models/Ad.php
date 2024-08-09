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
            Cache::forget("ads");
            Cache::forget("landingpage-html-20");
        });

        static::updating(function ($model) {
            if ($model->isDirty('image') && ($model->getOriginal('image') !== null)) {
                Storage::disk('public')->delete($model->getOriginal('image'));
            }
            Cache::forget("ads");
            Cache::forget("landingpage-html-20");
        });
    }
}
