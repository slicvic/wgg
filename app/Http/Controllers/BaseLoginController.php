<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class BaseLoginController extends BaseController
{
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
}
