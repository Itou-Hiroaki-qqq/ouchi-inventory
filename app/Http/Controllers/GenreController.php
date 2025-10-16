<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;

class GenreController extends Controller
{
    /**
     * ジャンル一覧を表示
     */
    public function index()
    {
        // ログインユーザーが登録したジャンルを取得
        $genres = Genre::where('user_id', Auth::id())->get();

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
}
