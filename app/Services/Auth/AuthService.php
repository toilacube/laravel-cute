<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService.php.
 */
class AuthService
{
    public function changepassword($userId, $passwordChangeDTO)
    {
        $user = User::find($userId);

        $currentPw = $user->password;

        if (!Hash::check($passwordChangeDTO->oldPassword, $currentPw)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->password = Hash::make($passwordChangeDTO->newPassword);
        $user->save();

        return response("Password changed successfully");
    }
}

