<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfile extends BaseController
{
    /**
     * Show a user's profile.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        // TODO
        return view('home.index');
    }
}
