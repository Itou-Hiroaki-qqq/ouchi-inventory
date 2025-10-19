<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ShareController;

// トップページへのアクセス時のリダイレクトを条件付きに変更
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/register');
});

// /dashboard を InventoryController の index() へルーティング
Route::get('/dashboard', [InventoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // プロフィール編集
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ジャンルの一覧表示・追加・編集・削除
    Route::resource('genres', GenreController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);

    // ジャンルごとのアイテム操作群
    Route::prefix('genres/{genre}')->group(function () {
        Route::get('items', [ItemController::class, 'index'])->name('items.index');
        Route::post('items', [ItemController::class, 'store'])->name('items.store');
        Route::get('items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::patch('items/{item}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

        // 数量を +1／−1
        Route::patch('items/{item}/increment', [ItemController::class, 'increment'])->name('items.increment');
        Route::patch('items/{item}/decrement', [ItemController::class, 'decrement'])->name('items.decrement');

        // 数量を手動入力で更新
        Route::patch('items/{item}/quantity', [ItemController::class, 'updateQuantity'])->name('items.updateQuantity');

        // 次回購入リストに追加
        Route::post('items/{item}/purchase', [PurchaseController::class, 'store'])->name('purchases.store');
    });

    // 次回購入リスト関連
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::delete('/purchases/{purchase}/cancel', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
    Route::patch('/purchases/{purchase}/complete', [PurchaseController::class, 'complete'])->name('purchases.complete');

    // 共有設定関連
    Route::get('/shares', [ShareController::class, 'index'])->name('shares.index'); // 自分が共有したユーザー一覧
    Route::post('/shares', [ShareController::class, 'store'])->name('shares.store'); // 共有追加
    Route::delete('/shares/{share}', [ShareController::class, 'destroy'])->name('shares.destroy'); // 共有解除
    Route::get('/shared-to-me', [ShareController::class, 'sharedToMe'])->name('shares.sharedToMe'); // 自分が共有された一覧
});

require __DIR__.'/auth.php';
