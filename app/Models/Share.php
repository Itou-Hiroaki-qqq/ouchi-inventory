<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Share extends Model
{
    /**
     * 複数代入可能なカラム
     */
    protected $fillable = [
        'owner_id',
        'shared_user_id',
    ];

    /**
     * この共有の持ち主（オーナー）ユーザー
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * この共有でアクセスできる共有ユーザー
     */
    public function sharedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_user_id');
    }
}
