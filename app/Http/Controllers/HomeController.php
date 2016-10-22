<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;

use App\Http\Controllers\BaseController;
use App\Models\Event;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
//        dd(Event::find(1)->type->name);
        return view('home.index');
    }
}
