<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Ulid extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Ulid::class;
    }
}
