<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    // このアイテムが属するジャンル
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    // このアイテムに関連する購入記録
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
