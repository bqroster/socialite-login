<?php

namespace Bqroster\SocialiteLogin\Traits\Models;

use Bqroster\SocialiteLogin\Models\UserSocial;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

/**
 * Trait UseSocialite
 * @package Bqroster\SocialiteLogin\Traits
 */
trait UseSocialite
{
    /**
     * @return array
     */
    public function getFillable()
    {
        return array_merge(
            $this->fillable,
            [
                'social_id',
                'social_token',
                'social_refresh_token',
                'nickname',
                'avatar_url',
                'register_with',
                'login_with',
            ]
        );
    }

    /**
     * @return array
     */
    public function getHidden()
    {
        return array_merge(
            $this->hidden,
            [
                'social_token',
                'social_refresh_token',
            ]
        );
    }

    /**
     * @return array
     */
    public function getCasts()
    {
        return array_merge(
            parent::getCasts(),
            [
                'social_token' => 'encrypted',
                'social_refresh_token' => 'encrypted',
            ]
        );
    }

    /**
     * @param string $socialLogin
     * @return bool
     */
    public function canLogin($socialLogin)
    {
        return (
            is_auto_login()
            && (
                (is_login_strict() && ($socialLogin === $this->register_with))
                || !is_login_strict()
            )
        );
    }
}