<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invitation;

class CheckAllUsersFriendStatusController extends Controller
{
    public function __invoke()
    {
        $currentUser = auth()->user();
        $users = User::where('id', '!=', $currentUser->id)->get();
    
        foreach ($users as $user) {
            $invitationSent = Invitation::where('sender_id', $currentUser->id)
                                        ->where('receiver_id', $user->id)
                                        ->first();
            $invitationReceived = Invitation::where('receiver_id', $currentUser->id)
                                            ->where('sender_id', $user->id)
                                            ->first();
    
            if ($invitationSent) {
                $user -> sender_id = $invitationSent -> sender_id;
                $user -> friend_status = $invitationSent -> status; // 'pending', 'accepted', etc.
            } elseif ($invitationReceived) {
                $user -> sender_id = $invitationReceived -> sender_id;
                $user -> friend_status = $invitationReceived -> status;
            } else {
                $user -> friend_status = 'not_friends';
            }
        }
    
        return response()->json([
            'users' => $users
        ]);
    }
}
