<?php

namespace Bqroster\SocialiteLogin\Helpers;

/**
 * Class SocialiteErrors
 * @package Bqroster\SocialiteLogin\Helpers
 */
class SocialiteErrors
{
    /**
     * @var string
     */
    const ERRORS_KEY = 'socialiteErrors';

    /**
     * @param string $socialDriver
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @return array
     */
    public static function emailNotPresent($socialDriver, $socialUser)
    {
        return [
            'driver' => $socialDriver,
            'email_not_present' => true,
            'user' => [
                'email' => null,
                'name' => $socialUser->getName(),
                'social_id' => $socialUser->getId(),
                'nickname' => $socialUser->getNickname(),
                'avatar_url' => $socialUser->getAvatar(),
            ],
        ];
    }

    /**
     * @param string $socialDriver
     * @return array
     */
    public static function userCancelled($socialDriver)
    {
        return [
            'driver' => $socialDriver,
            'user_cancelled' => true,
        ];
    }

    /**
     * @param string $socialDriver
     * @param mixed $user
     * @return array
     */
    public static function registerDiffLogin($socialDriver, $user)
    {
        return [
            'driver' => $socialDriver,
            'registered_diff_login' => true,
            'user' => [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'social_id' => $user->getSocialId(),
                'nickname' => $user->getSocialNickname(),
                'avatar_url' => $user->getAvatar(),
            ],
        ];
    }
}