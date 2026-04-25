<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Integration extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'integrations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'provider',
        'aws_user_arn',
        'gcp_id',
        'azure_oid',
        'redmine_url',
        'redmine_project_name',
        'redmine_project_identifier',
        'redmine_api_key',
        'slack_workspace_id',
        'slack_team_name',
        'slack_bot_token',
        'slack_user_id',
        'project_name',
        'is_active',
        'description',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'settings'  => 'array',
        // 本番では機密情報は暗号化推奨（Laravel 11以降）
        // 'redmine_api_key' => 'encrypted',
        // 'slack_bot_token'  => 'encrypted',
    ];

    /**
     * ユーザーとのリレーション
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * スコープ：Cloudのみ取得
     */
    public function scopeCloud($query)
    {
        return $query->where('type', 'cloud');
    }

    /**
     * スコープ：Redmineのみ取得
     */
    public function scopeRedmine($query)
    {
        return $query->where('type', 'redmine');
    }

    /**
     * スコープ：Slackのみ取得
     */
    public function scopeSlack($query)
    {
        return $query->where('type', 'slack');
    }

    /**
     * Cloudかどうか判定
     */
    public function isCloud(): bool
    {
        return $this->type === 'cloud';
    }

    /**
     * Redmineかどうか判定
     */
    public function isRedmine(): bool
    {
        return $this->type === 'redmine';
    }

    /**
     * Slackかどうか判定
     */
    public function isSlack(): bool
    {
        return $this->type === 'slack';
    }

    /**
     * Redmineが有効かどうか
     */
    public function hasActiveRedmine(): bool
    {
        return $this->isRedmine() && $this->is_active && !empty($this->redmine_url);
    }

    /**
     * Slackが有効かどうか
     */
    public function hasActiveSlack(): bool
    {
        return $this->isSlack() && $this->is_active && !empty($this->slack_workspace_id);
    }
}