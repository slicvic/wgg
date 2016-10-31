<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;

class UserProfile extends BaseController
{
    /**
     * Show a user's profile.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $events = Event::findAllByUserId($id);

        return view('user-profile.index', compact('user', 'events'));
    }
}
