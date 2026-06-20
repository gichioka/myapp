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
        Schema::create('retirements', function (Blueprint $table) {
            $table->id();
            // 社員テーブル（users）との紐付け（社員削除時に連動して削除）
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('社員ID');

            // 退職に関する情報
            $table->date('retired_at')->comment('退職日');
            $table->string('used_pc_info')->nullable()->comment('使っているPC（型番、資産管理番号など）');

            // アカウント削除・停止チェックリスト（初期値はすべて未完了: false）
            $table->boolean('has_ldap_deleted')->default(false)->comment('LDAPアカウント削除フラグ');
            $table->boolean('has_github_deleted')->default(false)->comment('GitHubアカウント削除フラグ');
            $table->boolean('has_slack_deleted')->default(false)->comment('Slackアカウント削除フラグ');
            $table->boolean('has_email_deleted')->default(false)->comment('メールアカウント削除フラグ');

            // 手続きの進捗管理用
            $table->string('status')->default('pending')->comment('手続きステータス（pending:未処理, processing:手続き中, completed:完了）');

            // 備品返却状況・PC初期化
            $table->string('pc_return_status')->default('unreturned')->comment('PC返却状況（unreturned:未返却, returned:返却済, lost:紛失など）');
            $table->date('pc_returned_at')->nullable()->comment('PC回収日');
            $table->date('pc_initialization_allowed_on')->nullable()->comment('PC初期化可能日（この日以降に初期化してOK）');

            $table->text('note')->nullable()->comment('備考（その他メモなど）');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retirements');
    }
};