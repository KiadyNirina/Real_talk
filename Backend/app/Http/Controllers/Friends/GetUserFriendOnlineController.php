<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invitation;

class GetUserFriendOnlineController extends Controller
{
    public function __invoke()
    {
        $userId = auth()->id();

        $friendIds = Invitation::where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->where('status', 'accepted') 
            ->pluck('sender_id', 'receiver_id')
            ->toArray(); 

        $friendIds = array_unique(array_merge(array_keys($friendIds), array_values($friendIds)));
        $friendIds = array_filter($friendIds, fn($id) => $id != $userId);

        $onlineFriends = User::whereIn('id', $friendIds)
                            ->where('is_online', true)
                            ->get();

        return response()->json($onlineFriends);
    }
}


