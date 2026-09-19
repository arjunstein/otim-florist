<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function (Model $model): void {
            if (! $model->isDirty('name') && $model->slug) {
                return;
            }

            $baseSlug = Str::slug($model->name) ?: Str::kebab(class_basename($model));
            $slug = $baseSlug;
            $suffix = 2;

            while (static::slugExists($model, $slug)) {
                $slug = "{$baseSlug}-{$suffix}";
                $suffix++;
            }

            $model->slug = $slug;
        });
    }

    private static function slugExists(Model $model, string $slug): bool
    {
        $query = $model->newQuery()->where('slug', $slug);

        if ($model->exists) {
            $query->whereKeyNot($model->getKey());
        }

        return $query->exists();
    }
}
