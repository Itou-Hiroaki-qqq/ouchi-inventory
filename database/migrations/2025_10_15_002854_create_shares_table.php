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
        Schema::create('shares', function (Blueprint $table) {
            $table->id();

            // 共有元（オーナー）
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            // 共有先（招待されたユーザー）
            $table->foreignId('shared_user_id')->constrained('users')->onDelete('cascade');

            // 同じ共有は複数作らせないように制約
            $table->unique(['owner_id', 'shared_user_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shares');
    }
};
