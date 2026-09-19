<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'description', 'image_path', 'category_id', 'price', 'sale_price'])]
class Product extends Model
{
    use HasSlug;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
