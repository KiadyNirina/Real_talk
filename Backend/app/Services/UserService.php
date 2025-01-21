<?php

namespace App\Services;

use App\Models\User;
use App\Validations\UserValidation;

class UserService
{
    public function getUserWithOthers($user)
    {
        $otherUsers = User::where('id', '!=', $user->id)->get()->map(function ($otherUser) {
            return [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'email' => $otherUser->email,
                'is_online' => $otherUser->isOnline(),
                'last_seen' => $otherUser->last_seen,
            ];
        });

        return [
            'user' => $user,
            'other_users' => $otherUsers,
        ];
    }

    public function getOnlineFriends()
    {
        return User::where('is_online', true)->get();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function updateUser($request)
    {
        $user = $request->user();

        $validator = UserValidation::validateUpdate($request->all(), $user->id);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $user->update($request->only(['name', 'email']));

        return ['user' => $user];
    }
}
