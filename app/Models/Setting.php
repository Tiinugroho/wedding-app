<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Cache container for loaded settings to prevent duplicate queries.
     */
    protected static $cache = [];

    /**
     * Get a setting by key.
     */
    public static function get($key, $default = null)
    {
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        $setting = self::where('key', $key)->first();
        $value = $setting ? $setting->value : $default;

        self::$cache[$key] = $value;

        return $value;
    }

    /**
     * Set a setting value by key.
     */
    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(['key' => $key], ['value' => $value]);
        self::$cache[$key] = $value;
        return $setting;
    }

    /**
     * Retrieve all settings as key-value array.
     */
    public static function getAll()
    {
        return self::all()->pluck('value', 'key')->toArray();
    }
}
