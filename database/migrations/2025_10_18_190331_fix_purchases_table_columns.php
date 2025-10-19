<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // genre_id がすでにあればスキップ
            if (! Schema::hasColumn('purchases', 'genre_id')) {
                $table->unsignedBigInteger('genre_id')->after('item_id');
            }
            // user_id がなければ追加
            if (! Schema::hasColumn('purchases', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('genre_id');
            }
            // 必要なら外部キー制約を追加する
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (Schema::hasColumn('purchases', 'genre_id')) {
                $table->dropColumn('genre_id');
            }
            if (Schema::hasColumn('purchases', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};
