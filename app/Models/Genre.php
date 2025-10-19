<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Genre extends Model
{
    // 所有者ユーザーとのリレーションを追加
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ジャンルに属するアイテムを取得
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
