<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Genre;
use App\Models\Item;
use App\Models\User;

class ShareService
{
    /**
     * ログインユーザーがジャンルにアクセスできるか判定
     */
    public function canAccessGenre(User $user, Genre $genre): bool
    {
        $userId = $user->id;

        // 所有者ならアクセス可
        if ($genre->user_id === $userId) {
            return true;
        }

        $ownerUser = $genre->user;
        if (!$ownerUser) {
            return false;
        }

        return $ownerUser
            ->sharedWith()
            ->where('shared_user_id', $userId)
            ->exists();
    }

    /**
     * アイテム設定ページの閲覧権限を判定（所有者または共有ユーザー）
     */
    public function canViewItem(User $user, Genre $genre, Item $item): bool
    {
        if ($item->genre_id !== $genre->id) {
            return false;
        }

        return $this->canAccessGenre($user, $genre);
    }

    /**
     * ログインユーザーがアクセス可能なジャンルID一覧を取得
     *
     * @return array<int>
     */
    public function getAccessibleGenreIds(?User $user = null): array
    {
        if (is_null($user)) {
            $user = Auth::user();
        }

        $userId = $user->id;

        $ownGenreIds = Genre::where('user_id', $userId)->pluck('id')->toArray();

        $sharedGenreIds = Genre::whereIn('user_id', function ($query) use ($userId) {
            $query->select('owner_id')
                ->from('shares')
                ->where('shared_user_id', $userId);
        })->pluck('id')->toArray();

        return array_merge($ownGenreIds, $sharedGenreIds);
    }

    /**
     * ジャンルを編集できるか判定（通常は所有者のみ）
     */
    public function canEditGenre(User $user, Genre $genre): bool
    {
        return $genre->user_id === $user->id;
    }

    /**
     * ジャンルを削除できるか判定（通常は所有者のみ）
     */
    public function canDeleteGenre(User $user, Genre $genre): bool
    {
        return $genre->user_id === $user->id;
    }
}
