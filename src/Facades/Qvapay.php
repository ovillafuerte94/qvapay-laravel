<?php

namespace Ovillafuerte94\QvapayLaravel\Facades;

use Ovillafuerte94\QvapayLaravel\ApiClient;
use Illuminate\Support\Facades\Facade;

class Qvapay extends Facade
{
    public static function getFacadeAccessor()
    {
        return ApiClient::class;
    }
}
