<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    // この購入が対象としているアイテム
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
