<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'domain',
        'subdomain',
        'database',
        'settings',
        'features',
        'status',
        'plan',
        'trial_ends_at',
        'subscription_ends_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'features' => 'array',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'active',
        'plan' => 'basic',
        'features' => '[]',
        'settings' => '{}',
    ];

    /**
     * Get all users for this tenant
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all roles for this tenant
     */
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    /**
     * Get all feature flags for this tenant
     */
    public function featureFlags(): HasMany
    {
        return $this->hasMany(FeatureFlag::class);
    }

    /**
     * Check if tenant has a specific feature enabled
     */
    public function hasFeature(string $feature): bool
    {
        return Cache::remember(
            "tenant.{$this->id}.feature.{$feature}",
            3600, // 1 hour cache
            function () use ($feature) {
                return $this->featureFlags()
                    ->where('feature_name', $feature)
                    ->where('is_enabled', true)
                    ->exists();
            }
        );
    }

    /**
     * Enable a feature for this tenant
     */
    public function enableFeature(string $feature, array $configuration = []): void
    {
        $this->featureFlags()->updateOrCreate(
            ['feature_name' => $feature],
            [
                'is_enabled' => true,
                'configuration' => $configuration,
                'enabled_at' => now(),
                'disabled_at' => null,
            ]
        );

        $this->clearFeatureCache($feature);
    }

    /**
     * Disable a feature for this tenant
     */
    public function disableFeature(string $feature): void
    {
        $this->featureFlags()->updateOrCreate(
            ['feature_name' => $feature],
            [
                'is_enabled' => false,
                'disabled_at' => now(),
            ]
        );

        $this->clearFeatureCache($feature);
    }

    /**
     * Get feature configuration
     */
    public function getFeatureConfiguration(string $feature): array
    {
        $flag = $this->featureFlags()
            ->where('feature_name', $feature)
            ->first();

        return $flag?->configuration ?? [];
    }

    /**
     * Check if tenant is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if tenant is on trial
     */
    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if tenant subscription is active
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    /**
     * Get tenant by domain
     */
    public static function findByDomain(string $domain): ?self
    {
        return Cache::remember(
            "tenant.domain.{$domain}",
            3600,
            fn() => static::where('domain', $domain)
                ->orWhere('subdomain', $domain)
                ->where('status', 'active')
                ->first()
        );
    }

    /**
     * Clear feature cache
     */
    protected function clearFeatureCache(string $feature): void
    {
        Cache::forget("tenant.{$this->id}.feature.{$feature}");
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($tenant) {
            // Clear domain cache when tenant is updated
            Cache::forget("tenant.domain.{$tenant->domain}");
            if ($tenant->subdomain) {
                Cache::forget("tenant.domain.{$tenant->subdomain}");
            }
        });

        static::deleted(function ($tenant) {
            // Clear all caches for this tenant
            Cache::forget("tenant.domain.{$tenant->domain}");
            if ($tenant->subdomain) {
                Cache::forget("tenant.domain.{$tenant->subdomain}");
            }
        });
    }

    /**
     * Scope to get active tenants only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get tenants on trial
     */
    public function scopeOnTrial($query)
    {
        return $query->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', now());
    }

    /**
     * Scope to get tenants with active subscriptions
     */
    public function scopeWithActiveSubscription($query)
    {
        return $query->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '>', now());
    }
}
