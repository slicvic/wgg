<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\BaseController;
use Facebook\Facebook;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        return view('home.index');
    }
}
