<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Genre;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use App\Services\ShareService;

class PurchaseController extends Controller
{
    protected ShareService $shareService;

    public function __construct(ShareService $shareService)
    {
        $this->shareService = $shareService;
    }

    /**
     * 次回購入リストの表示
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $genreIds = $this->shareService->getAccessibleGenreIds($user);

        $purchases = Purchase::with('item.genre')
            ->whereIn('genre_id', $genreIds)
            ->get();

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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$this->shareService->canAccessGenre($user, $genre) || $item->genre_id !== $genre->id) {
            abort(403);
        }

        // すでに登録されていない場合のみ作成
        if (!Purchase::where('item_id', $item->id)->exists()) {
            Purchase::create([
                'item_id'  => $item->id,
                'genre_id' => $genre->id,
                'user_id'  => $user->id,
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', '次回購入リストに追加しました。')
            ->with('from_route', 'purchases.store');
    }

    /**
     * 「購入やめ」処理（リストから削除）
     */
    public function destroy(Purchase $purchase, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $genre = $purchase->item->genre;

        if (!$this->shareService->canAccessGenre($user, $genre)) {
            abort(403);
        }

        $tab = $genre->name ?? null; // 現在のジャンル名を取得
        $purchase->delete();

        return redirect()->route('purchases.index', ['tab' => $tab])
            ->with('success', '購入リストから削除しました。');
    }

    /**
     * 「購入済み」処理（数量 +1 & リストから削除）
     */
    public function complete(Purchase $purchase, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $genre = $purchase->item->genre;

        if (!$this->shareService->canAccessGenre($user, $genre)) {
            abort(403);
        }

        $item = $purchase->item;
        $item->quantity += 1;
        $item->save();

        $tab = $genre->name ?? null; // 現在のジャンル名を取得
        $purchase->delete();

        return redirect()->route('purchases.index', ['tab' => $tab])
            ->with('success', '購入済みにしました。');
    }
}
