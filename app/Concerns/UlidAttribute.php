<?php

namespace App\Concerns;

use App\Facades\Ulid;
use Illuminate\Database\Eloquent\Model;

/**
 * Based on Rorecek\Ulid\HasUlid
 * https://github.com/rorecek/laravel-ulid/blob/master/src/HasUlid.php
 */
trait UlidAttribute
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->ulid) {
                $model->ulid = Ulid::generate();
            }
        });

        static::saving(function ($model) {
            $originalUlid = $model->getOriginal('id');
            if ($originalUlid !== $model->ulid) {
                $model->ulid = $originalUlid;
            }
        });
    }

    /**
     * Use a ulid to lookup a model.
     *
     * @param string $ulid
     * @return Model
     */
    public static function findByUlid($ulid)
    {
        return self::where('ulid', $ulid)->first();
    }

    /**
     * Use a ulid to lookup a model.
     *
     * @param string $ulid
     * @return Model
     */
    public static function findByUlidOrFail($ulid)
    {
        return self::where('ulid', $ulid)->firstOrFail();
    }
}
