<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'type',
        'status',
        'preferences',
        'meta',
        'last_login_at',
        'last_login_ip',
        'two_factor_enabled',
        'email_verified_at',
        'phone_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array',
            'meta' => 'array',
            'last_login_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'two_factor_recovery_codes' => 'array',
        ];
    }

    protected $attributes = [
        'type' => 'passenger',
        'status' => 'active',
        'preferences' => '{}',
        'meta' => '{}',
        'two_factor_enabled' => false,
    ];

    /**
     * Get the tenant that owns the user
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    /**
     * Check if user is staff
     */
    public function isStaff(): bool
    {
        return in_array($this->type, ['admin', 'staff']);
    }

    /**
     * Check if user is driver
     */
    public function isDriver(): bool
    {
        return $this->type === 'driver';
    }

    /**
     * Check if user is passenger
     */
    public function isPassenger(): bool
    {
        return $this->type === 'passenger';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if user is banned
     */
    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    /**
     * Get user's full name with title
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get user's initials for avatar
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }

    /**
     * Get user's avatar URL or generate one
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Generate avatar using initials
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . 
               "&color=ffffff&background=3b82f6&size=128";
    }

    /**
     * Update last login information
     */
    public function updateLastLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return Cache::remember(
            "user.{$this->id}.permission.{$permission}",
            3600,
            function () use ($permission) {
                // Check direct permissions
                if ($this->hasDirectPermission($permission)) {
                    return true;
                }
                
                // Check role permissions
                return $this->hasPermissionViaRole($permission);
            }
        );
    }

    /**
     * Check if user has permission via role
     */
    protected function hasPermissionViaRole(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if user has direct permission
     */
    protected function hasDirectPermission(string $permission): bool
    {
        // This would be implemented with Spatie Laravel Permission
        // For now, return false as placeholder
        return false;
    }

    /**
     * Get user preferences
     */
    public function getPreference(string $key, $default = null)
    {
        return data_get($this->preferences, $key, $default);
    }

    /**
     * Set user preference
     */
    public function setPreference(string $key, $value): void
    {
        $preferences = $this->preferences ?? [];
        data_set($preferences, $key, $value);
        
        $this->update(['preferences' => $preferences]);
    }

    /**
     * Scope to filter by tenant
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope to filter by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by status
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('type', 'admin');
    }

    /**
     * Scope to get staff users (admin + staff)
     */
    public function scopeStaff($query)
    {
        return $query->whereIn('type', ['admin', 'staff']);
    }

    /**
     * Scope to get drivers
     */
    public function scopeDrivers($query)
    {
        return $query->where('type', 'driver');
    }

    /**
     * Scope to get passengers
     */
    public function scopePassengers($query)
    {
        return $query->where('type', 'passenger');
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($user) {
            // Clear permission cache when user is updated
            Cache::forget("user.{$user->id}.permission.*");
        });
    }
}
