<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;

class ChatController extends Controller
{
    /**
     * Envoie un message à un utilisateur.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        // Validation des données
        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000', // Limite la longueur du message
        ]);

        // Création du message
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $data['receiver_id'],
            'message' => $data['message'],
        ]);

        // Déclenche l'événement MessageSent
        event(new MessageSent($message));

        // Retourne le message créé
        return response()->json(['message' => $message], 201);
    }

    /**
     * Récupère les messages entre l'utilisateur connecté et un autre utilisateur.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessage($userId)
    {
        // Vérifie que l'utilisateur existe
        if (!User::where('id', $userId)->exists()) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Récupère les messages entre les deux utilisateurs
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')
        ->get();

        // Retourne les messages
        return response()->json(['messages' => $messages]);
    }
}