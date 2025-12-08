<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Metrics extends Facade
{
    public const CONTAINER_KEY = 'metrics';

    protected static function getFacadeAccessor()
    {
        return self::CONTAINER_KEY;
    }
}
