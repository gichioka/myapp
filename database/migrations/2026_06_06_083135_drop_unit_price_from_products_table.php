<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // 単価カラム削除
            $table->dropColumn(['unit_price']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // rollback用に復元
            $table->unsignedBigInteger('unit_price')
                  ->default(0)
                  ->after('quantity');
        });
    }
};