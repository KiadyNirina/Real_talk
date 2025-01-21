<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUser(Request $request)
    {
        return response()->json($this->userService->getUserWithOthers($request->user()));
    }

    public function getUserFriendOnline()
    {
        return response()->json($this->userService->getOnlineFriends());
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function updateUser(Request $request)
    {
        $result = $this->userService->updateUser($request);

        if (isset($result['errors'])) {
            return response()->json(['errors' => $result['errors']], 422);
        }

        return response()->json([
            'message' => 'Informations updated with success!',
            'user' => $result['user']
        ]);
    }
}
