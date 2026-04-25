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
    Schema::create('integrations', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('user_id')
              ->constrained()
              ->onDelete('cascade');

        // ここから必須カラム
        $table->enum('type', ['cloud', 'redmine', 'slack'])->index();

        $table->enum('provider', ['aws', 'gcp', 'azure'])->nullable();
        
        $table->string('aws_user_arn')->nullable();
        $table->string('gcp_id')->nullable();
        $table->string('azure_oid')->nullable();

        $table->string('redmine_url')->nullable();
        $table->string('redmine_project_name')->nullable();
        $table->string('redmine_project_identifier')->nullable();
        $table->string('redmine_api_key')->nullable();

        $table->string('slack_workspace_id')->nullable();
        $table->string('slack_team_name')->nullable();
        $table->string('slack_bot_token')->nullable();
        $table->string('slack_user_id')->nullable();

        $table->string('project_name')->nullable();
        $table->boolean('is_active')->default(true);
        $table->text('description')->nullable();
        $table->json('settings')->nullable();

        $table->timestamps();

        // ユニーク制約
        $table->unique(['user_id', 'type', 'provider']);
        $table->unique(['user_id', 'type']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
