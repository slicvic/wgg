<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

use App\Exceptions\AccountDeactivatedException;
use App\Services\RegistrarService;

class LoginController extends BaseController
{
    /**
     * @var RegistrarService
     */
    protected $registrarService;

    /**
     * Constructor.
     *
     * @param RegistrarService $registrarService
     */
    public function __construct(RegistrarService $registrarService)
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
            // Create/update user
            $user = $this->registrarService->facebook();

            // Log user in
            Auth::loginUsingId($user->id);

            if ($redirectUrl = $request->query('success_redirect')) {
                return redirect($redirectUrl);
            }
        } catch(FacebookResponseException $e) {
            $this->flashError(sprintf('A Facebook login error occurred. %s Please try again.', $e->getMessage()));
        } catch(FacebookSDKException $e) {
            $this->flashError(sprintf('A Facebook login error occurred. %s Please try again.', $e->getMessage()));
        } catch(AccountDeactivatedException $e) {
            $this->flashError($e->getMessage());
        } catch (\Exception $e) {
            $this->flashError('A Facebook login error occurred, please try again.');
        }

        return redirect()->route('home');
    }
}
