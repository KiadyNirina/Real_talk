<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\User;
use App\Events\MessageSent;
use App\Models\Message;

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

    public function acceptInvitation($invitationId)
    {
        $invitation = Invitation::where('id', $invitationId)
                            ->where('receiver_id', auth()->id())
                            ->first();

        if (!$invitation) {
            return response()->json(['message' => 'Invitation not found or not authorized'], 404);
        }

        $invitation->status = 'accepted'; // Mettre à jour le statut de l'invitation à "accepted"
        $invitation->save();

        return response()->json($invitation);
    }

    public function rejectInvitation($invitationId)
    {
        $invitation = Invitation::where('id', $invitationId)
                            ->where('receiver_id', auth()->id())
                            ->first();

        if (!$invitation) {
            return response()->json(['message' => 'Invitation not found or not authorized'], 404);
        }

        $invitation->status = 'rejected'; // Mettre à jour le statut de l'invitation à "rejected"
        $invitation->save();

        return response()->json($invitation);
    }

    public function checkFriendStatus($selectedUserId) 
    {
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
    
        return response()->json($friendship);
    }

    public function getFriends() 
    {
        $user = auth()->id();
    
        // Chercher une relation d'amitié existante
        $friendship = Invitation::where(function($query) use ($user) {
                $query->where('sender_id', $user)
                ->orWhere('receiver_id', $user);
            })
            ->where('status', 'accepted')
            ->get();

        // On va récupérer les informations des amis
        $friendUsers = $friendship->map(function($friend) use ($user) {
            return $friend->sender_id == $user ? $friend->receiver : $friend->sender;
        });
    
        return response()->json($friendUsers);
    }

    public function getUserFriendOnline()
    {

        $userId = auth()->id();

        // Récupérer les invitations acceptées où l'utilisateur est soit l'expéditeur soit le destinataire
        $friendIds = Invitation::where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->where('status', 'accepted') // Filtrer les amitiés acceptées
            ->pluck('sender_id', 'receiver_id')
            ->toArray(); // Récupérer sous forme d'un tableau

        // Fusionner les IDs d'amis en excluant l'utilisateur lui-même
        $friendIds = array_unique(array_merge(array_keys($friendIds), array_values($friendIds)));
        $friendIds = array_filter($friendIds, fn($id) => $id != $userId);

        // Récupérer les amis qui sont en ligne
        $onlineFriends = User::whereIn('id', $friendIds)
                            ->where('is_online', true)
                            ->get();

        // Retourner la liste des amis en ligne
        return response()->json($onlineFriends);
    }

    public function checkAllUsersFriendStatus() 
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
                $user -> friend_status = 'not_friends'; // pas encore d'invitation
            }
        }
    
        return response()->json([
            'users' => $users
        ]);
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        /* $message = Message::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]); */

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'Message sent!']);
    }

    public function getMessage($selectedUserId) 
    {
        $user = auth()->id();
    
        $message = Message::where(function($query) use ($user, $selectedUserId) {
                $query->where('sender_id', $user)
                      ->where('receiver_id', $selectedUserId);
            })
            ->orWhere(function($query) use ($user, $selectedUserId) {
                $query->where('sender_id', $selectedUserId)
                      ->where('receiver_id', $user);
            })
            ->get();
    
        return response()->json($message);
    }

    
}
