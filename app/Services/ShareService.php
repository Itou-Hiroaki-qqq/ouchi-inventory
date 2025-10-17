<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Genre;

class ShareService
{
    /**
     * ログインユーザーがジャンルにアクセスできるか判定
     */
    public function canAccessGenre($user, Genre $genre): bool
    {
        // $user は \App\Models\User 型である前提
        $userId = $user->id;

        // 所有者ならアクセス可
        if ($genre->user_id === $userId) {
            return true;
        }

        // 共有されているかどうか
        return $genre->user
            ->sharedWith()
            ->where('shared_user_id', $userId)
            ->exists();
    }

    /**
     * ログインユーザーがアクセス可能なジャンルID一覧を取得
     *
     * @return array<int>
     */
    public function getAccessibleGenreIds($user = null): array
    {
        if (is_null($user)) {
            $user = Auth::user();
        }

        $userId = $user->id;

        // 自分のジャンルID
        $ownGenreIds = Genre::where('user_id', $userId)->pluck('id')->toArray();

        // 共有されているジャンルID（他のユーザーのジャンルを共有先として持つ）
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
    public function canEditGenre($user, Genre $genre): bool
    {
        return $genre->user_id === $user->id;
    }

    /**
     * ジャンルを削除できるか判定（通常は所有者のみ）
     */
    public function canDeleteGenre($user, Genre $genre): bool
    {
        return $genre->user_id === $user->id;
    }
}
