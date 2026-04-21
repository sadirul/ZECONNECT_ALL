<?php

namespace App\Models;

use App\Models\Concerns\TracksChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Metadata extends Model
{
    use TracksChanges;

    protected $table = 'metadata';

    public const INPUT_TYPES = [
        'text' => 'Text',
        'number' => 'Number',
        'textarea' => 'Textarea',
        'boolean' => 'Boolean (Yes/No)',
        'email' => 'Email',
        'url' => 'URL',
        'date' => 'Date',
        'time' => 'Time',
        'datetime' => 'Date & Time',
        'color' => 'Color',
        'tel' => 'Phone',
        'search' => 'Search',
    ];

    protected $fillable = [
        'key',
        'value',
        'input_type',
    ];

    protected $attributes = [
        'input_type' => 'text',
    ];

    /**
     * Get metadata value by key.
     */
    public static function getMetaData(string $key, $default = null): mixed
    {
        $value = Cache::remember(
            "metadata:{$key}",
            now()->addHours(24),
            fn () => static::query()
                ->where('key', $key)
                ->value('value')
        );

        return $value ?? $default;
    }

    protected static function booted(): void
    {
        static::saved(function (self $metadata): void {
            Cache::forget("metadata:{$metadata->key}");
        });

        static::deleted(function (self $metadata): void {
            Cache::forget("metadata:{$metadata->key}");
        });
    }
}
