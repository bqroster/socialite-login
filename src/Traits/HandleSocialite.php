<?php

namespace Bqroster\SocialiteLogin\Traits;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

/**
 * Trait HandleSocialite
 * @package Bqroster\SocialiteLogin\Traits
 */
trait HandleSocialite
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function login()
    {
        return $this->redirectToSocialite($this->socialite_driver);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function register()
    {
        return $this->redirectToSocialite($this->socialite_driver);
    }

    /**
     * @param string $driver
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectToSocialite(string $driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $driver
     * @return \Laravel\Socialite\Contracts\User|null
     */
    private function handleCallback(Request $request, string $driver)
    {
        if ($this->isErrorsOnResponse($request)) {
            /**
             * TODO, need to handle
             * errors, cancel, etc
             */
            return null;
        }

        try {
            $socialUser = Socialite::driver($driver)->user();
        } catch (\Exception $exception) {
            /**
             * TODO, Log this exception
             */
            $socialUser = null;
        }

        return $socialUser;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isErrorsOnResponse(Request $request)
    {
        if (
            /**
             * @facebook
             */
            $request->has('error')
            || $request->has('error_reason')
            || $request->has('error_code')
            /**
             * @google
             */
            || $request->has('error')
            /**
             * @twitter
             */
            || $request->has('denied')
        ) {
            /**
             * TODO, Log request and errors
             */
            return true;
        }

        return false;
    }

    /**
     * @param \Laravel\Socialite\Contracts\User
     * @return bool
     */
    private function isEmailOnResponse($socialUser)
    {
        // confirm `email` is present
        if (!(property_exists($socialUser, 'email') && !empty($socialUser->email))) {
            return false;
        }

        return true;
    }

    /**
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @param string $socialLogin
     * @return mixed
     */
    private function handleCreateSocialUser($socialUser, $socialLogin)
    {
        /**
         * check if user must be auto created
         */
        if (!can_user_auto_create()) {
            return null;
        }

        $modelRelationship = socialite_relationship_model();
        $user = $modelRelationship::firstOrCreate(
            ['email' => $socialUser->email],
            [
                'first_name' => $socialUser->name,
                'email' => $socialUser->email,
                'password' => bcrypt(\Str::random(12)),
                'avatar' => $socialUser->avatar,
                'register_with' => $socialLogin,
                'login_with' => is_auto_login() ? $socialLogin : null,
            ]
        );

        if (!$user->wasRecentlyCreated && $user->canLogin($socialLogin)) {
            $user->login_with = $socialLogin;
            $user->saveQuietly();
        }

        $user->socialites()->create([
            'response' => $socialUser
        ]);

        return $user;
    }
}