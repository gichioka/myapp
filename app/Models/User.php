<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'employment_type',
        'is_retired',
        'comment',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function serverAccounts()
    {
        return $this->hasMany(ServerAccount::class);
    }
    
    public function toolUsages()
    {
        return $this->hasMany(ToolUsage::class);
    }
    
    public function products(): HasMany
    {
    return $this->hasMany(Product::class);
    }

    public function integrations()
    {
    return $this->hasMany(Integration::class);
    }

    public function clouds()   
    {
    return $this->integrations()->where('type', 'cloud');
    }

    public function redmines()
    {
    return $this->integrations()->where('type', 'redmine');
    }

    public function slacks()
    {
    return $this->integrations()->where('type', 'slack');
    }

    }