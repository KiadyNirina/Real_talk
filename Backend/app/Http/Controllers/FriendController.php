<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;

class FriendController extends Controller
{
    public function sendInvitation(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $invitation = Invitation::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->receiver_id,
        ]);

        return response()->json(['message' => 'Invitation send with success!']);
    }

    public function acceptInvitation(Invitation $invitation)
    {
        $invitation->update(['status' => 'accepted']);

        return response()->json(['message' => 'Invitation accepted!']);
    }

    public function rejectInvitation(Invitation $invitation)
    {
        $invitation->update(['status' => 'rejected']);

        return response()->json(['message' => 'Invitation rejected!']);
    }

    public function checkFriendStatus($selectedUserId) {
        $user = auth()->id();
    
        // Chercher une relation d'amitié existante
        $friendship = Invitation::where(function($query) use ($user, $selectedUserId) {
                $query->where('sender_id', $user)
                      ->where('receiver_id', $selectedUserId);
            })
            ->orWhere(function($query) use ($user, $selectedUserId) {
                $query->where('sender_id', $selectedUserId)
                      ->where('receiver_id', $user);
            })
            ->first();
    
        // Vérifier le statut de la relation
        if (!$friendship) {
            return response()->json(['status' => 'not_friends']);
        }
    
        if ($friendship->status == 'accepted') {
            return response()->json(['status' => 'friends']);
        }
    
        if ($friendship->status == 'pending') {
            return response()->json(['status' => 'pending']);
        }
    
        return response()->json(['status' => 'not_friends']);
    }
    
}
