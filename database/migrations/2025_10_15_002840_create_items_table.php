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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            // 所属するジャンル（外部キー）
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            // 所属するユーザー（オーナー）※共有対応しやすくするため
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 商品名
            $table->string('name');
            // 在庫数量（初期値は0）
            $table->unsignedInteger('quantity')->default(0);
            // 次回購入予定フラグ（true/false）
            $table->boolean('next_purchase')->default(false);
            // よく使う順ソート用（＋ボタンが押された合計回数など）
            $table->unsignedInteger('total_added_count')->default(0);
            // 備考メモ
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
