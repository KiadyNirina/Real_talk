<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;

class UserValidation
{
    public static function validateUpdate($data, $userId)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
        ]);
    }
}
