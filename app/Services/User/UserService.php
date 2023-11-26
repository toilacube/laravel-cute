<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Address;
use App\Models\UserAddress;
use App\DTOs\Requests\RegisterDTO;
use App\DTOs\Responses\AddressDTO;

/**
 * Class UserService.
 */
class UserService
{
    private $true = "true";
    private $false = "false";
    public function createUser(RegisterDTO $registerDTO)
    {
        return User::create([
            'email' => $registerDTO->getEmail(),
            'password' => bcrypt($registerDTO->getPassword()),
        ]);
    }

    public function findUser($userId)
    {
        return User::find($userId);
    }

    public function findAddresses($userId)
    {
        $addresses = [];
        $userAddresses = (User::where('id', $userId)->with('addresses')->get()[0]['addresses']);
        foreach ($userAddresses as $address) {
            $addressDTO = new AddressDTO(
                $address['id'],
                $address['address_line'],
                $address['pivot']['is_default']
            );
            $addresses[] = $addressDTO->toArray();
        }
        return  response($addresses);
    }

    public function addAddress($userId, $address)
    {
        $address = Address::create([
            'address_line' => $address,
        ]);
        if ($address) {
            $user = User::find($userId);
            $user->addresses()->attach($address->id);

            UserAddress::where('user_id', $user->id)
                ->where('address_id', $address->id)
                ->update(['is_default' => 0]);
            return $this->true;
        }
        return $this->false;
    }

    public function MakeAddressDefault($userId, $addressId)
    {
        $user = User::find($userId);
        $user->addresses()->updateExistingPivot($addressId, ['is_default' => 1]);
        UserAddress::where('user_id', $user->id)
            ->where('address_id', '!=', $addressId)
            ->update(['is_default' => 0]);
        return $this->true;
    }
}
