<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Invitation;

class AcceptInvitationController extends Controller
{
    public function __invoke($invitationId)
    {
        $invitation = Invitation::where('id', $invitationId)
            ->where('receiver_id', auth()->id())
            ->first();

        if (!$invitation) {
            return response()->json(['message' => 'Invitation not found or not authorized'], 404);
        }

        $invitation->update(['status' => 'accepted']);

        DB::table('friends')->insert([
            ['user_id' => $invitation->sender_id, 'friend_id' => $invitation->receiver_id],
            ['user_id' => $invitation->receiver_id, 'friend_id' => $invitation->sender_id],
        ]);

        return response()->json($invitation);
    }
}
