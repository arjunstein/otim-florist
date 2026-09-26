<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'phone', 'address', 'hours', 'google_reviews_url', 'google_rating', 'google_reviews_count'])]
class StoreSetting extends Model
{
    protected function casts(): array
    {
        return [
            'google_rating' => 'float',
            'google_reviews_count' => 'integer',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([
            'id' => 1,
        ], [
            'name' => 'Otim Florist',
            'phone' => '',
            'address' => 'Jl. Mawar No. 12, Jakarta',
            'hours' => '08:00–20:00 daily',
            'google_reviews_url' => 'https://share.google/v4xDWRoD4ANg5Ft3K',
            'google_rating' => 5.0,
            'google_reviews_count' => 27,
        ]);
    }
}
