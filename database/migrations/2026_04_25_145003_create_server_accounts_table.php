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
        Schema::create('server_accounts', function (Blueprint $table) {
            $table->id();

            // ===== 基本情報 =====
            $table->string('category');      // SSH / DB など
            $table->string('label');         // 本番サーバーなど
            $table->string('account_name');  // ec2-user など

            // ===== ユーザー紐付け =====
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // ===== 認証情報 =====
            $table->text('password');        // Hashで保存

            // ===== オプション =====
            $table->string('host')->nullable();
            $table->text('note')->nullable();

            // ===== タイムスタンプ =====
            $table->timestamps();

            // ===== インデックス =====
            $table->index('category');
            $table->index('label');
            $table->index('account_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_accounts');
    }
};