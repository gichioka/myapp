<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('developer_accounts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('developer_accounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('developer_accounts', function (Blueprint $table) {
            $table->unique(['user_id', 'tool_type']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};