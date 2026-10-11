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

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_permissions');
    }

    // ---------------------------------------------------------------
    // RBAC Helpers
    // ---------------------------------------------------------------

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }

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

        // 1. Direct assigned permissions
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('admin_permissions')) {
                if ($this->relationLoaded('permissions')) {
                    if ($this->permissions->contains('name', $permission)) {
                        return true;
                    }
                } else {
                    if ($this->permissions()->where('name', $permission)->exists()) {
                        return true;
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore error if table not yet migrated
        }

        // 2. Role-based permissions
        try {
            return $this->roles()
                ->whereHas('permissions', fn ($q) => $q->where('name', $permission))
                ->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getAllPermissions(): \Illuminate\Support\Collection
    {
        if ($this->is_super_admin) {
            return Permission::all();
        }

        $rolePerms = collect();
        try {
            $rolePerms = $this->roles->flatMap->permissions;
        } catch (\Throwable $e) {}

        $directPerms = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('admin_permissions')) {
                $directPerms = $this->permissions;
            }
        } catch (\Throwable $e) {}

        return $rolePerms->merge($directPerms)->unique('id');
    }

    public function getRoleDisplayNameAttribute(): string
    {
        if ($this->is_super_admin) {
            return 'Super Administrator';
        }

        $role = $this->roles->first();
        if ($role) {
            return $role->display_name ?? $role->name;
        }

        $permCount = $this->getAllPermissions()->count();
        if ($permCount > 0) {
            return 'Custom Subadmin (' . $permCount . ' perms)';
        }

        return 'Subadmin / Staff';
    }

    // ---------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSubadmins($query)
    {
        return $query->where('is_super_admin', false);
    }
}
