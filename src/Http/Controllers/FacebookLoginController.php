<?php

namespace Bqroster\SocialiteLogin\Http\Controllers;

/**
 * Class FacebookLoginController
 * @package Bqroster\SocialiteLogin\Http\Controllers
 */
class FacebookLoginController extends LoginSocialController
{
    /**
     * @var string
     */
    protected $socialite_driver = 'facebook';
}