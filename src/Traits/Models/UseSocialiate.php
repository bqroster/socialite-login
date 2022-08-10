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
}