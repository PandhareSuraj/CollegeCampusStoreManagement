<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Setting Model
 * 
 * Manages system-wide configuration settings
 */
class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'value',
        'type',
        'group',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope: Get by key
     */
    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    /**
     * Scope: Get by group
     */
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get setting value by key
     */
    public static function getValue(string $key, $default = null)
    {
        return self::where('key', $key)->value('value') ?? $default;
    }

    /**
     * Set setting value
     */
    public static function setValue(string $key, $value): void
    {
        self::where('key', $key)->update(['value' => $value]);
    }

    /**
     * Get all settings by group
     */
    public static function getGroup(string $group): array
    {
        return self::where('group', $group)->pluck('value', 'key')->toArray();
    }
}
