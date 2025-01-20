<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Controller;
use App\Models\Invitation;

class GetFriendsController extends Controller
{
    public function __invoke()
    {
        $user = auth()->id();

        $friendships = Invitation::where(function ($query) use ($user) {
            $query->where('sender_id', $user)
                  ->orWhere('receiver_id', $user);
        })->where('status', 'accepted')->get();

        $friendUsers = $friendships->map(function ($friend) use ($user) {
            return $friend->sender_id == $user ? $friend->receiver : $friend->sender;
        });

        return response()->json($friendUsers);
    }
}
