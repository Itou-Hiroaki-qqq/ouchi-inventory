<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * ジャンルごとのアイテム一覧を表示
     */
    public function index(Genre $genre)
    {
        if ($genre->user_id !== Auth::id()) {
            abort(403);
        }

        $items = $genre->items()->orderByDesc('total_added_count')->get();

        return view('items.index', [
            'genre' => $genre,
            'items' => $items
        ]);
    }

    /**
     * 新しいアイテムを登録
     */
    public function store(Request $request, Genre $genre)
    {
        if ($genre->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $item = new Item();
        $item->genre_id = $genre->id;
        $item->name = $request->name;
        $item->quantity = 0;
        $item->total_added_count = 0;
        $item->save();

        return redirect()->route('items.index', $genre)->with('success', 'アイテムを追加しました。');
    }

    /**
     * アイテム編集画面表示
     */
    public function edit(Genre $genre, Item $item)
    {
        if ($genre->user_id !== Auth::id() || $item->genre_id !== $genre->id) {
            abort(403);
        }

        return view('items.edit', [
            'genre' => $genre,
            'item' => $item,
        ]);
    }

    /**
     * アイテム更新処理
     */
    public function update(Request $request, Genre $genre, Item $item)
    {
        if ($genre->user_id !== Auth::id() || $item->genre_id !== $genre->id) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $item->name = $request->name;

        if (!is_null($request->quantity)) {
            $item->quantity = $request->quantity;
        }

        $item->note = $request->note;
        $item->save();

        return redirect()->route('items.index', $genre)->with('success', 'アイテムを更新しました。');
    }

    /**
     * アイテム削除処理
     */
    public function destroy(Genre $genre, Item $item)
    {
        if ($genre->user_id !== Auth::id() || $item->genre_id !== $genre->id) {
            abort(403);
        }

        $item->delete();

        return redirect()->route('items.index', $genre)->with('success', 'アイテムを削除しました。');
    }

    /**
     * 数量を +1 する
     */
    public function increment(Genre $genre, Item $item)
    {
        if ($genre->user_id !== Auth::id() || $item->genre_id !== $genre->id) {
            abort(403);
        }

        $item->quantity += 1;
        $item->total_added_count += 1; // よく消費する順ソート用カウント
        $item->save();

        return redirect()->route('items.index', $genre)->with('success', '数量を増やしました。');
    }

    /**
     * 数量を -1 する（0未満にはならない）
     */
    public function decrement(Genre $genre, Item $item)
    {
        if ($genre->user_id !== Auth::id() || $item->genre_id !== $genre->id) {
            abort(403);
        }

        if ($item->quantity > 0) {
            $item->quantity -= 1;
            $item->save();
        }

        return redirect()->route('items.index', $genre)->with('success', '数量を減らしました。');
    }
}
