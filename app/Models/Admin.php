<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'avatar',
        'is_super_admin', 'status', 'last_login_at', 'last_login_ip',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'is_super_admin'    => 'boolean',
            'password'          => 'hashed',
        ];
    }

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_roles');
    }

    // ---------------------------------------------------------------
    // RBAC Helpers
    // ---------------------------------------------------------------

    public function hasRole(string $role): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->where('name', $permission))
            ->exists();
    }

    public function getAllPermissions(): \Illuminate\Support\Collection
    {
        if ($this->is_super_admin) {
            return Permission::all();
        }

        return $this->roles->flatMap->permissions->unique('id');
    }

    // ---------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
