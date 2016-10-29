<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;

use App\Models\Event;

class HomeController extends BaseController
{
    /**
     * Show the home page.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('home.index');
    }
}
