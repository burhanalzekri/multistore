<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type'];

    protected static function booted(): void
    {
        static::saved(function () { Cache::forget('site_settings'); });
        static::deleted(function () { Cache::forget('site_settings'); });
    }

    public static function allCached(): array
    {
        return Cache::rememberForever('site_settings', function () {
            return self::all()
                ->mapWithKeys(function ($s) {
                    return [$s->group . '.' . $s->key => self::cast($s->value, $s->type)];
                })
                ->toArray();
        });
    }

    protected static function cast($value, $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number'  => is_numeric($value) ? (float) $value : 0,
            'json'    => json_decode($value, true) ?: [],
            default   => $value,
        };
    }
}
