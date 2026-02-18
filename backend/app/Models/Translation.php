<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Translation extends Model
{
    protected $fillable = [
        'locale',
        'key',
        'value',
        'approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $translation) {
            Cache::forget("i18n:translations:{$translation->locale}");
        });

        static::deleted(function (self $translation) {
            Cache::forget("i18n:translations:{$translation->locale}");
        });
    }

}
