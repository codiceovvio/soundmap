<?php

namespace CodiceOvvio\Soundmap\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CodiceOvvio\Soundmap\Soundmap
 */
class Soundmap extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \CodiceOvvio\Soundmap\Soundmap::class;
    }
}
