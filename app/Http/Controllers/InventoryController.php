<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Genre;
use App\Services\ShareService;

class InventoryController extends Controller
{
    protected ShareService $shareService;

    public function __construct(ShareService $shareService)
    {
        $this->shareService = $shareService;
    }

    public function index()
    {
        $user = Auth::user();

        // アクセス可能なジャンルを取得
        $accessibleGenreIds = $this->shareService->getAccessibleGenreIds($user);
        $genres = Genre::whereIn('id', $accessibleGenreIds)
            ->orderBy('name', 'asc') // 並び順
            ->get();

        // 各ジャンルに共有・編集・削除を付与
        foreach ($genres as $genre) {
            $genre->isShared = $genre->user_id !== $user->id;
            $genre->canEdit = $this->shareService->canEditGenre($user, $genre);
            $genre->canDelete = $this->shareService->canDeleteGenre($user, $genre);

            // 各ジャンルに関連するアイテム名を確認
            logger()->info("Genre: {$genre->name}, Items: " . $genre->items->pluck('name'));
        }

        return view('dashboard', [
            'user' => $user,
            'genres' => $genres,
        ]);
    }
}
