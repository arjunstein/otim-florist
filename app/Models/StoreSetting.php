<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'phone', 'address', 'hours'])]
class StoreSetting extends Model
{
    public static function current(): self
    {
        return static::query()->firstOrCreate([
            'id' => 1,
        ], [
            'name' => 'Otim Florist',
            'phone' => '',
            'address' => 'Jl. Mawar No. 12, Jakarta',
            'hours' => '08:00–20:00 daily',
        ]);
    }
}
