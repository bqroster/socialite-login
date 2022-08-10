<?php

namespace Bqroster\SocialiteLogin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Bqroster\SocialiteLogin\Traits\HandleSocialite;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

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
     * @return \Laravel\Socialite\Contracts\User|null
     */
    public function callback(Request $request)
    {
        if ($socialUser = $this->handleCallback($request, $this->socialite_driver)) {
            if ($this->isEmailOnResponse($socialUser)) {

                $user = $this->handleCreateSocialUser($socialUser);

                if (!is_null($user) && (config('socialite-login.actions.login') === true)) {
                    Auth::guard()->login($user, false);
                }
            }
        }

        if ($redirect = session()->get(config('socialite-login.redirect.sessionKey'))) {
            return redirect($redirect);
        }

        return redirect(config('socialite-login.redirect.fallback'));
    }

    public function cancelled(Request $request)
    {
        dd('cancelled', $request->all());
    }

    public function removed(Request $request)
    {
        dd('removed', $request->all());
    }
}
