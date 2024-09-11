<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broadcaster extends Model
{
    use HasFactory;

    public function subscribers()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
