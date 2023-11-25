<?php

namespace App\Services\User;

use App\Models\User;
use App\DTOs\Requests\RegisterDTO;

/**
 * Class UserService.
 */
class UserService
{
    public function createUser(RegisterDTO $registerDTO)
    {
        return User::create([
            'email' => $registerDTO->getEmail(),
            'password' => bcrypt($registerDTO->getPassword()),
        ]);
    }
}
