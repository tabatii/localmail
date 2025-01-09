<?php

namespace Tabatii\LocalMail\Facades;

use Illuminate\Support\Facades\Facade;

class LocalMail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'localmail';
    }
}
