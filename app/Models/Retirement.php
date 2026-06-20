<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retirement extends Model
{
    use HasFactory;

    /**
     * 複数代入を許可する属性（マスアサインメント）
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'retired_at',
        'used_pc_info',
        'has_ldap_deleted',
        'has_github_deleted',
        'has_slack_deleted',
        'has_email_deleted',
        'status',
        'pc_return_status',
        'pc_returned_at',
        'pc_initialization_allowed_on',
        'note',
    ];

    /**
     * ネイティブな型へキャストする属性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'retired_at' => 'date',
        'pc_returned_at' => 'date',
        'pc_initialization_allowed_on' => 'date',
        'has_ldap_deleted' => 'boolean',
        'has_github_deleted' => 'boolean',
        'has_slack_deleted' => 'boolean',
        'has_email_deleted' => 'boolean',
    ];

    /**
     * 退職情報に紐づく社員（User）を取得
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}