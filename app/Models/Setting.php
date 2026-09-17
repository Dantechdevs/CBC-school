<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type', 'is_encrypted'];

    protected $casts = [
        'is_encrypted' => 'boolean',
    ];

    public const CACHE_KEY = 'app_settings_all';

    /**
     * Get a single setting value by key, decoded/decrypted per its type.
     * Falls back to $default if not set in DB (so config/school.php values
     * still work until someone actually saves a setting).
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::allCached();

        if (!array_key_exists($key, $all)) {
            return $default;
        }

        return $all[$key];
    }

    /**
     * Set (create or update) a setting, cache is flushed.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'string', bool $encrypt = false): self
    {
        $stored = match (true) {
            $encrypt              => Crypt::encryptString((string) $value),
            $type === 'json'      => json_encode($value),
            $type === 'bool'      => $value ? '1' : '0',
            default               => (string) $value,
        };

        $setting = static::updateOrCreate(
            ['key' => $key],
            ['group' => $group, 'value' => $stored, 'type' => $type, 'is_encrypted' => $encrypt]
        );

        Cache::forget(self::CACHE_KEY);

        return $setting;
    }

    public static function setMany(array $keyValuePairs, string $group, array $typeMap = [], array $encryptedKeys = []): void
    {
        foreach ($keyValuePairs as $key => $value) {
            static::set(
                $key,
                $value,
                $group,
                $typeMap[$key] ?? 'string',
                in_array($key, $encryptedKeys, true)
            );
        }
    }

    /** All settings, decoded, cached for 1 hour, keyed by `key`. */
    public static function allCached(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return static::all()->mapWithKeys(function (Setting $s) {
                return [$s->key => self::decode($s)];
            })->toArray();
        });
    }

    public static function forGroup(string $group): array
    {
        return static::where('group', $group)->get()->mapWithKeys(function (Setting $s) {
            return [$s->key => self::decode($s)];
        })->toArray();
    }

    protected static function decode(Setting $s): mixed
    {
        if ($s->is_encrypted) {
            try {
                return Crypt::decryptString($s->value);
            } catch (\Throwable) {
                return null; // corrupted / key rotated — fail closed, not with a 500
            }
        }

        return match ($s->type) {
            'bool' => (bool) $s->value,
            'int'  => (int) $s->value,
            'json' => json_decode($s->value, true),
            default => $s->value,
        };
    }

    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
