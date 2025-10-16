<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Genre;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    /**
     * 次回購入リストの表示
     */
    public function index()
    {
        $userId = Auth::id();

        // ログインユーザーが持つジャンルのID一覧
        $genreIds = Genre::where('user_id', $userId)->pluck('id');

        // 対象ジャンルに含まれる購入リストを取得（アイテムとジャンルも一緒に取得）
        $purchases = Purchase::with('item.genre')
            ->whereIn('genre_id', $genreIds)
            ->get();

        // ジャンル名ごとにグループ化（ビューでジャンル別に表示）
        $groupedPurchases = $purchases->groupBy(function ($purchase) {
            return $purchase->item->genre->name ?? '未分類';
        });

        return view('purchases.index', [
            'groupedPurchases' => $groupedPurchases,
        ]);
    }

    /**
     * 次回購入に追加
     */
    public function store(Request $request, Genre $genre, Item $item)
    {
        $userId = Auth::id();

        // 所有者チェック
        if ($genre->user_id !== $userId || $item->genre_id !== $genre->id) {
            abort(403);
        }

        // 重複登録防止
        if (!Purchase::where('item_id', $item->id)->exists()) {
            Purchase::create([
                'item_id' => $item->id,
                'genre_id' => $genre->id,
            ]);
        }

        return back()->with('success', '次回購入リストに追加しました。');
    }

    /**
     * 「購入やめ」処理（リストから削除）
     */
    public function destroy(Purchase $purchase)
    {
        $this->authorizeAccess($purchase);

        $purchase->delete();

        return back()->with('success', '購入リストから削除しました。');
    }

    /**
     * 「購入済み」処理（数量 +1 & リストから削除）
     */
    public function complete(Purchase $purchase)
    {
        $this->authorizeAccess($purchase);

        $item = $purchase->item;
        $item->quantity += 1;
        $item->save();

        $purchase->delete();

        return back()->with('success', '購入済みにしました。');
    }

    /**
     * 購入データの所有者確認（不正アクセス防止）
     */
    private function authorizeAccess(Purchase $purchase)
    {
        $userId = Auth::id();

        if ($purchase->item->genre->user_id !== $userId) {
            abort(403);
        }
    }
}
