<?php

namespace App\Http\Controllers\Friends;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RemoveFriendController extends Controller
{
    public function __invoke($friendId)
    {
        $userId = auth()->id();

        DB::table('friends')->where(function($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)
                  ->where('friend_id', $friendId);
        })->orWhere(function($query) use ($userId, $friendId) {
            $query->where('user_id', $friendId)
                  ->where('friend_id', $userId);
        })->delete();

        DB::table('invitations')->where(function($query) use ($userId, $friendId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $friendId);
        })->orWhere(function($query) use ($userId, $friendId) {
            $query->where('sender_id', $friendId)
                  ->where('receiver_id', $userId);
        })->delete();

        return response()->json(['message' => 'Friend and corresponding invitations removed successfully']);
    }
}
