<?php

declare(strict_types=1);

namespace EgyptianNationalId\Facades;

use Illuminate\Support\Facades\Facade;

class NationalId extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'national-id';
    }
}
