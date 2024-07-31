<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->category_name);
    }
}
