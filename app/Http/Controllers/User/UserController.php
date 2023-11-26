<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\DTOs\UserDTO\UserInfoDTO;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }
    //
    public function user(Request $request)
    {
        $userId = $request->user()->id;
        $user = $this->userService->findUser($userId);
        return $user;
    }
    public function updateInfo(Request $request)
    {
        $userId = $request->user()->id;
        $user = $this->userService->findUser($userId);
        $userInfoDTO = new UserInfoDTO(
            $request->name,
            $request->email,
            $request->phoneNumber
        );
        $user->update($userInfoDTO->toArray());
        return $user;
    }

    public function getAddresses(Request $request)
    {
        $userId = $request->user()->id;
        return $this->userService->findAddresses($userId);
    }

    public function addAddress(Request $request)
    {
        $userId = $request->user()->id;
        $address = $this->userService->addAddress($userId, $request->getContent());
        return $address;
    }
    
    public function MakeAddressDefault(Request $request)
    {
        $userId = $request->user()->id;
        $addressId = $request->getContent();
        $address = $this->userService->MakeAddressDefault($userId, $addressId);
        return $address;
    }
}
