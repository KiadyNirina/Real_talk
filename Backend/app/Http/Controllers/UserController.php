<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = $request->user();
        
        // Récupérer tous les autres utilisateurs
        $otherUsers = User::where('id', '!=', $user->id)->get()->map(function($otherUser) {
            // Ajouter le statut en ligne à chaque utilisateur
            return [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'email' => $otherUser->email,
                'is_online' => $otherUser->isOnline(),  // Vérifier si l'utilisateur est en ligne
                'last_seen' => $otherUser->last_seen,
            ];
        });

        // Retourner l'information sous forme de réponse JSON
        return response()->json([
            'user' => $user,
            'other_users' => $otherUsers,
        ]);
    }

    public function getUserFriendOnline(Request $request)
    {
        // Récupérer les amis de l utilisateur connécté qui sont en ligne
        $onlineUsers = User::where('is_online', '=', true)->get();

        return response()->json($onlineUsers);
    }

    public function show($id)
    {
        // Récupérer l'utilisateur par son ID
        $user = User::find($id);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Retourner les informations de l'utilisateur
        return response()->json($user);
    }

    public function updateUser(Request $request) {
        $user = $request->user();

        $validator = User::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($request->only(['name', 'email']));

        return response()->json(['message' => 'Informations updated with success !', 'user' => $user]);
    }
}
