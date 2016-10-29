<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
     * Flash success message.
     *
     * @param string $message
     */
    protected function flashSuccess($message)
    {
        session()->flash('success', $message);
    }

    /**
     * Redirect to a path with an error message.
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

    /**
     * Redirect back with an error message.
     *
     * @param  string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBackWithError($message)
    {
        $this->flashError($message);

        return redirect()->back();
    }

    /**
     * Redirect back with a success message.
     *
     * @param  string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBackWithSuccess($message)
    {
        $this->flashSuccess($message);

        return redirect()->back();
    }
}
