<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\Auth\Guard;

abstract class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Flash error message.
     *
     * @param string $message
     */
    protected function flashError($message)
    {
        session()->flash('error', $message);
    }

    /**
     * Redirect to a given path with a given error message.
     *
     * @param  string $path
     * @param  string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectWithError($path, $message)
    {
        $this->flashError($message);

        return redirect()->route($path);
    }
}
