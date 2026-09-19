<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'category_id', 'price'])]
class Product extends Model
{
    use HasSlug;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
