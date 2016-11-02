<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use App\Contracts\RegistrarServiceInterface;
use App\Exceptions\AccountDeactivatedException;

class LoginController extends BaseController
{
    /**
     * @var RegistrarService
     */
    protected $registrarService;

    /**
     * Constructor.
     *
     * @param RegistrarServiceInterface $registrarService
     */
    public function __construct(RegistrarServiceInterface $registrarService)
    {
        $this->registrarService = $registrarService;
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
     * Log in a Facebook user.
     *
     * @param  Request $request
     * @return \Illuminate\Routing\Redirector
     */
    public function facebook(Request $request)
    {
        try {
            // Create new user or update existing
            $user = $this->registrarService->facebook();

            // Log the user in
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
