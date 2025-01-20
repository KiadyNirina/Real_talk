<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use App\Models\Invitation;

class RejectInvitationController extends Controller
{
    public function __invoke($invitationId)
    {
        $invitation = Invitation::where('id', $invitationId)
            ->where('receiver_id', auth()->id())
            ->first();

        if (!$invitation) {
            return response()->json(['message' => 'Invitation not found or not authorized'], 404);
        }

        $invitation->delete();

        return response()->json($invitation);
    }
}
