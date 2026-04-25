<?php  

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolUsage extends Model
{
    protected $fillable = [
        'user_id',
        'tool_name',
        'version',
        'memo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}