<?php
namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Models\Event;

class EventsController extends BaseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the current user's events.
     *
     * @param  Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $events = Event::findAllByUserId(Auth::user()->id);

        return view('account.events.index', compact('events'));
    }
}
