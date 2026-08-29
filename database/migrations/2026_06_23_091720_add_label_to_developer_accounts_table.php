```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('developer_accounts', 'label')) {
            Schema::table('developer_accounts', function (Blueprint $table) {
                $table->string('label')->nullable()->after('tool_type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('developer_accounts', 'label')) {
            Schema::table('developer_accounts', function (Blueprint $table) {
                $table->dropColumn('label');
            });
        }
    }
};
