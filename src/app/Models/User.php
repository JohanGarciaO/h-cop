<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'username',
        'name',
        'document',
        'phone',
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getRoleLabelAttribute()
    {
        $roleName = $this->role?->name;

        $predefinedNames = [
            'ADMIN' => 'administrador',
            'OPERATOR' => 'operador',
        ];

        return $predefinedNames[$roleName] ?? $roleName;
    }

    public function isAdmin(): bool
    {
        return $this->role_id === 1;
    }

    public function isOperator(): bool
    {
        return $this->role_id === 2;
    }

    public function getFirstNameAttribute(): string
    {   
        $first = trim(strtok($this->name, ' '));
        return ucfirst(strtolower($first));
    }
}
