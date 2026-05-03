<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerAccount extends Model
{
    protected $fillable = [
        'category',
        'label',
        'account_name',
        'user_id',
        'password',
        'host',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $term)
    {
        if (!$term) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('label', 'like', "%{$term}%")
              ->orWhere('account_name', 'like', "%{$term}%")
              ->orWhere('host', 'like', "%{$term}%");
        });
    }

    public function scopeByCategory($query, $category)
    {
        if (!$category) return $query;
        return $query->where('category', $category);
    }
}