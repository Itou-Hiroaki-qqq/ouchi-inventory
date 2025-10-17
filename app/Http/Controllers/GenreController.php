<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use App\Services\ShareService;

class GenreController extends Controller
{
    protected ShareService $shareService;

    public function __construct(ShareService $shareService)
    {
        $this->shareService = $shareService;
    }

    /**
     * ジャンル一覧を表示
     */
    public function index()
    {
        $user = Auth::user();

        $accessibleGenreIds = $this->shareService->getAccessibleGenreIds($user);

        $genres = Genre::whereIn('id', $accessibleGenreIds)->get();

        foreach ($genres as $genre) {
            $genre->isShared = $genre->user_id !== $user->id;
            $genre->canEdit = $this->shareService->canEditGenre($user, $genre);
            $genre->canDelete = $this->shareService->canDeleteGenre($user, $genre);
        }

        return view('genres.index', [
            'genres' => $genres
        ]);
    }

    /**
     * ジャンルを新規追加
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $genre = new Genre();
        $genre->user_id = Auth::id();
        $genre->name = $request->name;
        $genre->save();

        return redirect()->route('genres.index')->with('success', 'ジャンルを追加しました。');
    }

    /**
     * ジャンル編集画面表示
     */
    public function edit(Genre $genre)
    {
        $user = Auth::user();

        // 編集権限チェック
        if (! $this->shareService->canEditGenre($user, $genre)) {
            abort(403);
        }

        return view('genres.edit', [
            'genre' => $genre,
        ]);
    }

    /**
     * ジャンル更新処理
     */
    public function update(Request $request, Genre $genre)
    {
        $user = Auth::user();

        if (! $this->shareService->canEditGenre($user, $genre)) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $genre->name = $request->name;
        $genre->save();

        return redirect()->route('genres.index')->with('success', 'ジャンルを更新しました。');
    }

    /**
     * ジャンル削除処理
     */
    public function destroy(Genre $genre)
    {
        $user = Auth::user();

        if (! $this->shareService->canDeleteGenre($user, $genre)) {
            abort(403);
        }

        $genre->delete();

        return redirect()->route('genres.index')->with('success', 'ジャンルを削除しました。');
    }
}
