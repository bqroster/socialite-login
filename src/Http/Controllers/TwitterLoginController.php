<?php

namespace Bqroster\SocialiteLogin\Http\Controllers;

/**
 * Class TwitterLoginController
 * @package Bqroster\SocialiteLogin\Http\Controllers
 */
class TwitterLoginController extends LoginSocialController
{
    /**
     * @var string
     */
    protected $socialite_driver = 'twitter';
}