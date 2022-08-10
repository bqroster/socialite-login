<?php

namespace Bqroster\SocialiteLogin\Http\Controllers;

/**
 * Class GoogleLoginController
 * @package Bqroster\SocialiteLogin\Http\Controllers
 */
class GoogleLoginController extends LoginSocialController
{
    /**
     * @var string
     */
    protected $socialite_driver = 'google';
}