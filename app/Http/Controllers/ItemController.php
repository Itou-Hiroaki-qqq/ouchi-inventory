<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Genre;
use App\Models\User;
use App\Services\ShareService;

class ItemController extends Controller
{
    protected ShareService $shareService;

    public function __construct(ShareService $shareService)
    {
        $this->shareService = $shareService;
    }

    public function index(Genre $genre)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->shareService->canAccessGenre($user, $genre)) {
            abort(403);
        }

        $items = $genre->items()->orderByDesc('total_added_count')->get();
        $canEditGenre = $this->shareService->canEditGenre($user, $genre);

        foreach ($items as $item) {
            $item->canEdit = $canEditGenre;
            $item->canDelete = $this->shareService->canDeleteGenre($user, $genre);
        }

        return view('items.index', [
            'genre' => $genre,
            'items' => $items,
            'canEditGenre' => $canEditGenre,
            'isShared' => $genre->user_id !== $user->id,
        ]);
    }

    /**
     * アイテム登録（登録後はトップページにリダイレクト）
     */
    public function store(Request $request, Genre $genre)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->shareService->canAccessGenre($user, $genre)) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $item = new Item();
        $item->genre_id = $genre->id;
        $item->user_id = $user->id;
        $item->name = $request->name;
        $item->quantity = 0;
        $item->total_added_count = 0;
        $item->save();

        return redirect()->route('dashboard')->with('success', 'アイテムを追加しました。');
    }

    public function edit(Genre $genre, Item $item)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->shareService->canViewItem($user, $genre, $item) || $item->genre_id !== $genre->id) {
            abort(403);
        }

        return view('items.edit', [
            'genre' => $genre,
            'item'  => $item,
        ]);
    }

    /**
     * アイテム更新（更新後はトップページへ）
     */
    public function update(Request $request, Genre $genre, Item $item)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->shareService->canAccessGenre($user, $genre) || $item->genre_id !== $genre->id) {
            abort(403);
        }

        $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $item->note = $request->note;
        $item->save();

        return redirect()->route('dashboard')->with('success', 'メモを保存しました。');
    }

    /**
     * アイテム削除（削除後はトップページへ）
     */
    public function destroy(Genre $genre, Item $item)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->shareService->canAccessGenre($user, $genre) || $item->genre_id !== $genre->id) {
            abort(403);
        }

        $item->delete();

        return redirect()->route('dashboard')->with('success', 'アイテムを削除しました。');
    }

    /**
     * 数量を +1（処理後はトップページへ）
     */
    public function increment(Genre $genre, Item $item)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->shareService->canAccessGenre($user, $genre) || $item->genre_id !== $genre->id) {
            abort(403);
        }

        $item->quantity += 1;
        $item->total_added_count += 1;
        $item->save();

        return redirect()->route('dashboard')->with('success', '数量を増やしました。');
    }

    /**
     * 数量を -1（処理後はトップページへ）
     */
    public function decrement(Genre $genre, Item $item)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$this->shareService->canAccessGenre($user, $genre) || $item->genre_id !== $genre->id) {
            abort(403);
        }

        if ($item->quantity > 0) {
            $item->quantity -= 1;
            $item->save();
        }

        return redirect()->route('dashboard')->with('success', '数量を減らしました。');
    }
}
