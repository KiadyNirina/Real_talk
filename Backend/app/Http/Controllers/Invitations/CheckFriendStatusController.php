<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Models\Invitation;

class CheckFriendStatusController extends Controller
{
    public function __invoke($selectedUserId)
    {
        $user = auth()->id();
    
        $friendship = Invitation::where(function($query) use ($user, $selectedUserId) {
                $query->where('sender_id', $user)
                      ->where('receiver_id', $selectedUserId);
            })
            ->orWhere(function($query) use ($user, $selectedUserId) {
                $query->where('sender_id', $selectedUserId)
                      ->where('receiver_id', $user);
            })
            ->first();
    
        return response()->json($friendship);
    }
}
