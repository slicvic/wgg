<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use App\Contracts\SocialLoginInterface;
use App\Exceptions\AccountDeactivatedException;

class LoginController extends BaseController
{
    /**
     * @var SocialLoginInterface
     */
    protected $socialLoginService;

    /**
     * Constructor.
     *
     * @param SocialLoginInterface $socialLoginService
     */
    public function __construct(SocialLoginInterface $socialLoginService)
    {
        $this->socialLoginService = $socialLoginService;
    }

    /**
     * Log out the current user.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }

    /**
     * Log in a facebook user.
     *
     * @param  Request $request
     * @return \Illuminate\Routing\Redirector
     */
    public function facebook(Request $request)
    {
        try {
            $user = $this->socialLoginService->registerWithFacebook();

            Auth::loginUsingId($user->id);

            if ($redirectUrl = $request->query('redirect')) {
                return redirect($redirectUrl);
            }
        } catch (FacebookResponseException $e) {
            $this->flashError(trans('messages.system.login.error.facebook', ['error' => $e->getMessage()]));
        } catch (FacebookSDKException $e) {
            $this->flashError(trans('messages.system.login.error.facebook', ['error' => $e->getMessage()]));
        } catch (AccountDeactivatedException $e) {
            $this->flashError($e->getMessage());
        } catch (\Exception $e) {
            $this->flashError(trans('messages.system.login.error.facebook', ['error' => '']));
        }

        return redirect()->route('home');
    }
}
