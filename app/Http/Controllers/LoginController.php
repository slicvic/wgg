<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use App\Exceptions\AccountDeactivatedException;
use App\Services\Socializer;

class LoginController extends BaseController
{
    /**
     * @var Socializer
     */
    protected $socializer;

    /**
     * Constructor.
     *
     * @param Socializer $socializer
     */
    public function __construct(Socializer $socializer)
    {
        $this->socializer = $socializer;
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
            $user = $this->socializer->registerWithFacebook();

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
