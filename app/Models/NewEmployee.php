<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewEmployee extends Model
{
    protected $fillable = [
        'applicant_name',
        'name',
        'email',
        'department',
        'join_date',
        'status',
        'needs_github',
        'needs_redmine',
        'needs_svn',
        'needs_google_drive',
        'needs_unity',
        'needs_maya',
        'remarks',
    ];

    protected $casts = [
        'join_date'          => 'date',
        'needs_github'       => 'boolean',
        'needs_redmine'      => 'boolean',
        'needs_svn'          => 'boolean',
        'needs_google_drive' => 'boolean',
        'needs_unity'        => 'boolean',
        'needs_maya'         => 'boolean',
    ];

    public const STATUSES = ['予定', '入社済', '辞退'];

    public const TOOLS = [
        'needs_github'       => 'GitHub',
        'needs_redmine'      => 'Redmine',
        'needs_svn'          => 'SVN',
        'needs_google_drive' => 'Google Drive',
        'needs_unity'        => 'Unity',
        'needs_maya'         => 'Maya',
    ];
}