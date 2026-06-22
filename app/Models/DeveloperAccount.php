<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeveloperAccount extends Model
{
    protected $fillable = [
        'user_id',
        'tool_type',
        'label',
        'url',
        'username',
        'password',
    ];

    protected function casts(): array
    {
        return [
            // パスワードを保存時に自動で暗号化し、取得時に自動で復号
            'password' => 'encrypted',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}