<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Event;

class ProfileController extends BaseController
{
    /**
     * Show the given user profile.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $events = Event::findAllByUserId($id);

        return view('user.profile.index', compact('user', 'events'));
    }
}
