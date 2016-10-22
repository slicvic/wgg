<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\BaseController;

abstract class BaseLoginController extends BaseController
{
    /**
     * Logs out the current user.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
