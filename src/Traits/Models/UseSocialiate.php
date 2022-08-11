<?php

namespace Bqroster\SocialiteLogin\Traits\Models;

use Bqroster\SocialiteLogin\Models\UserSocial;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

/**
 * Trait UseSocialiate
 * @package Bqroster\SocialiteLogin\Traits
 */
trait UseSocialiate
{
    /**
     * @return mixed
     */
    public function socialites()
    {
        return $this->hasMany(UserSocial::class);
    }

    /**
     * @return mixed
     */
    public function latestSocialites()
    {
        return $this->socialites->sortByDesc('created_at')->first();
    }

    /**
     * @return array
     */
    public function getFillable()
    {
        return array_merge(
            $this->fillable,
            ['avatar', 'register_with', 'login_with']
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