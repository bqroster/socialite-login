<?php

namespace Bqroster\SocialiteLogin\Http\Controllers;

use Bqroster\SocialiteLogin\Helpers\SocialiteErrors;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Bqroster\SocialiteLogin\Traits\HandleSocialite;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 * @package Bqroster\SocialiteLogin\Http\Controllers
 */
class LoginSocialController extends BaseController
{
    use DispatchesJobs,
        HandleSocialite,
        ValidatesRequests,
        AuthorizesRequests;

    /**
     * @var string
     */
    protected $socialite_driver;

    /**
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        $user = null;
        $redirectTo = redirect_url_session_key($user) ?? redirect_url_fallback($user);

        if ($socialUser = $this->handleCallback($request, $this->socialite_driver)) {
            if ($this->isEmailOnResponse($socialUser)) {
                $user = $this->handleCreateSocialUser($socialUser, $this->socialite_driver);

                if (!is_null($user)) {
                    return $this->handleAutomaticLogin($user, $this->socialite_driver);
                }
            } else {
                /**
                 * @email not present
                 * on social auth
                 */
                $redirectTo->with('socialiteErrors', SocialiteErrors::emailNotPresent($this->socialite_driver, $socialUser));
            }
        } else {
            /**
             * @something went wrong
             * user cancelled social auth,
             * etc
             */
            $redirectTo->with('socialiteErrors', SocialiteErrors::userCancelled($this->socialite_driver));
        }

        return $redirectTo;
    }
}
