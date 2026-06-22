<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('developer_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // 4つのツールを識別するEnum型
            $table->enum('tool_type', ['github', 'svn', 'redmine', 'docker']);
            
            // 接続先URL
            $table->string('url')->nullable();
            
            // 各ツールのユーザー名やアカウントID
            $table->string('username')->nullable();
            
            // 暗号化して保存するパスワード・トークン
            $table->text('password');

            $table->timestamps();

            // 1人のユーザーが各ツール1レコードだけ持てるように制限
            $table->unique(['user_id', 'tool_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developer_accounts');
    }
};