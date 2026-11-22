<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'description', 'image_path', 'thumbnail_path', 'category_id', 'price', 'sale_price'])]
class Product extends Model
{
    use HasSlug;

    public function thumbnailUrl(): ?string
    {
        $path = $this->thumbnail_path ?? $this->image_path;

        return $path ? Storage::disk('public')->url($path) : null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
