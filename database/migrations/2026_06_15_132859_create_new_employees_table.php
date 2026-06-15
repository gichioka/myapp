<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('new_employees', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_name');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('department')->nullable();
            $table->date('join_date');
            $table->enum('status', ['予定', '入社済', '辞退'])->default('予定');

            $table->boolean('needs_github')->default(false);
            $table->boolean('needs_redmine')->default(false);
            $table->boolean('needs_svn')->default(false);
            $table->boolean('needs_google_drive')->default(false);
            $table->boolean('needs_unity')->default(false);
            $table->boolean('needs_maya')->default(false);

            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('new_employees');
    }
};