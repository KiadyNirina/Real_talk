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
}
