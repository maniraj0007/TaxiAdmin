<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureFlag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'feature_name',
        'is_enabled',
        'configuration',
        'enabled_at',
        'disabled_at',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'configuration' => 'array',
        'enabled_at' => 'datetime',
        'disabled_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns the feature flag
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope to get enabled features
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * Scope to get disabled features
     */
    public function scopeDisabled($query)
    {
        return $query->where('is_enabled', false);
    }

    /**
     * Scope to filter by feature name
     */
    public function scopeForFeature($query, string $featureName)
    {
        return $query->where('feature_name', $featureName);
    }

    /**
     * Check if feature is currently enabled
     */
    public function isCurrentlyEnabled(): bool
    {
        return $this->is_enabled && 
               (!$this->disabled_at || $this->disabled_at->isFuture());
    }

    /**
     * Get feature configuration value
     */
    public function getConfigValue(string $key, $default = null)
    {
        return data_get($this->configuration, $key, $default);
    }

    /**
     * Set feature configuration value
     */
    public function setConfigValue(string $key, $value): void
    {
        $config = $this->configuration ?? [];
        data_set($config, $key, $value);
        
        $this->update(['configuration' => $config]);
    }
}
