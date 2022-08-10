<?php

namespace Bqroster\SocialiteLogin\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class SocialiteLogin
 * @package Bqroster\SocialiteLogin\Facades
 */
class SocialiteLogin extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'socialite-login';
    }
}