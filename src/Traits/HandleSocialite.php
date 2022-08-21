<?php

namespace Bqroster\SocialiteLogin\Traits;

use Bqroster\SocialiteLogin\Helpers\SocialiteErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return null;
        }

        try {
            $socialUser = Socialite::driver($driver)->user();
        } catch (\Exception $exception) {
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
     * @param \App\Models\User|mixed $user
     * @param string $socialDriver
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|null
     */
    private function handleAutomaticLogin($user, $socialDriver)
    {
        if ($loginClass = is_login_a_class()) {
            $loginInstance = new $loginClass;
            $redirect = $loginInstance->handle($user, $socialDriver);
            if ($redirect) {
                return $redirect;
            }
        } else if ($user->canLogin($socialDriver)) {
            Auth::guard()->login($user, false);
        }

        $redirectTo = redirect_url_session_key($user) ?? redirect_url_fallback($user);

        if (!$user->canLoginVsRegister($socialDriver)) {
            $redirectTo->with(
                SocialiteErrors::ERRORS_KEY,
                SocialiteErrors::registerDiffLogin($socialDriver, $user)
            );
        }

        return $redirectTo;
    }

    /**
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @param string $socialLogin
     * @return mixed
     */
    private function handleCreateSocialUser($socialUser, $socialLogin)
    {
        if ($createClass = can_user_create_is_a_class()) {
            $createInstance = new $createClass;
            return $createInstance->handle($socialUser, $socialLogin);
        }

        /**
         * check if user must be auto created
         */
        if (!can_user_auto_create()) {
            return null;
        }

        $user = $this->createSocialUser($socialUser, $socialLogin);

        $this->updateSocialUserLoginTokens($user, $socialUser, $socialLogin);

        return $user;
    }

    /**
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @param string $socialLogin
     * @return \App\Models\User|mixed
     */
    private function createSocialUser($socialUser, $socialLogin)
    {
        $modelRelationship = socialite_relationship_model();
        return $modelRelationship::firstOrCreate(
            ['email' => $socialUser->email],
            [
                'name' => $socialUser->getName(),
                'social_nickname' => $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(),
                'password' => bcrypt(\Str::random(12)),
                'social_id' => $socialUser->getId(),
                'avatar_url' => $socialUser->getAvatar(),
                'register_with' => $socialLogin,
                'login_with' => is_auto_login() ? $socialLogin : null,
                'social_token' => $socialUser->token,
                'social_refresh_token' => $socialUser->refreshToken ?? null,
            ]
        );
    }

    /**
     * @param \App\Models\User|mixed $user
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @param string $socialLogin
     */
    private function updateSocialUserLoginTokens($user, $socialUser, $socialLogin)
    {
        if (!$user->wasRecentlyCreated && $user->canLogin($socialLogin)) {
            $user->login_with = $socialLogin;
            $user->social_token = $socialUser->token;
            $user->social_refresh_token = $socialUser->refreshToken ?? null;
            if (save_quietly_on_create()) {
                $user->saveQuietly();
            } else {
                $user->save();
            }
        }
    }
}