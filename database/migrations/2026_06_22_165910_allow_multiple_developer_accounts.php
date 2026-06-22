<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('developer_accounts', function (Blueprint $table) {
            // 既存のユニーク制約を削除（インデックス名：テーブル名_カラム1_カラム2_unique）
            $table->dropUnique('developer_accounts_user_id_tool_type_unique');
            
            // 識別しやすくするために「ラベル（例: 社内用、個人用）」カラムを追加
            $table->string('label')->nullable()->after('tool_type');
        });
    }

    public function down(): void
    {
        Schema::table('developer_accounts', function (Blueprint $table) {
            $table->dropColumn('label');
            $table->unique(['user_id', 'tool_type']);
        });
    }
};