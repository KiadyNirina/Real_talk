<?php

namespace App\Http\Controllers\Invitations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invitation;

class SendInvitationController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        Invitation::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->receiver_id,
        ]);

        return response()->json(['message' => 'Invitation sent with success!']);
    }
}
