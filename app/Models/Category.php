<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

#[Fillable(['name'])]
class Category extends Model
{
    use HasSlug;

    public const CACHE_KEY_NAVIGATION = 'navigation_categories';

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY_NAVIGATION));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY_NAVIGATION));
    }

    /**
     * @return array<int, array{name: string, slug: string}>
     */
    public static function navigation(): array
    {
        return Cache::rememberForever(self::CACHE_KEY_NAVIGATION, function () {
            return static::query()
                ->orderBy('name')
                ->get(['name', 'slug'])
                ->map(fn (self $category) => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                ])
                ->all();
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
