<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            // 所属するユーザー
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // 購入対象アイテム
            $table->foreignId('item_id')->constrained()->onDelete('cascade');

            // 購入ステータス（pending = 次回購入予定中 / completed = 購入済み / canceled = キャンセル）
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');

            // 購入日時（購入完了時のみ記録したい場合など）
            $table->timestamp('purchased_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
