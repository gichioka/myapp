<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 品名
            $table->string('name');

            // ブランド
            $table->string('brand')->nullable();

            // 管理番号
            $table->string('sku')->unique();

            // カテゴリ
            $table->string('category')->nullable();

            // CPU
            $table->string('cpu')->nullable();

            // メモリ
            $table->unsignedInteger('ram')->nullable();

            // ストレージ
            $table->string('storage')->nullable();

            // 在庫数
            $table->unsignedInteger('quantity')
                ->default(0);

            // 単価
            $table->unsignedBigInteger('unit_price')
                ->default(0);

            // 備考
            $table->text('description')
                ->nullable();

            $table->timestamps();

            // 検索高速化
            $table->index('name');
            $table->index('cpu');
            $table->index('ram');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};