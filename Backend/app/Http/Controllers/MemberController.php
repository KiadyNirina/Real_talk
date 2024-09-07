<?php

namespace App\Http\Controllers;

use App\Models\User; // Remplace 'User' par ton modèle de membres
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $user = auth() -> user();
        // Récupérer tous les membres depuis la base de données
        $members = User::where('id', '!=', $user -> id)->get(); // Si tu utilises un autre modèle, remplace 'User' par 'Member' ou autre

        // Retourner les membres sous format JSON
        return response()->json($members);
    }
}