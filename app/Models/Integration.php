<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Integration extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'project_name',
        'is_active',
        // Cloud
        'provider',
        'aws_user_arn',
        'gcp_id',
        'azure_oid',
        // Redmine
        'redmine_url',
        'redmine_project_identifier',
        'redmine_api_key',
        // Slack
        'slack_workspace_id',
        'slack_team_name',
        'slack_bot_token',
        'slack_user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'redmine_api_key',
        'slack_bot_token',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}