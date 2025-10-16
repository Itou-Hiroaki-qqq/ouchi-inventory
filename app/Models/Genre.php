<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genre extends Model
{
    // ジャンルに属するアイテムを取得
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
