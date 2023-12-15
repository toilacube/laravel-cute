<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Address;
use App\Models\UserAddress;
use App\DTOs\Requests\RegisterDTO;
use App\DTOs\Responses\AddressDTO;
use App\DTOs\UserDTO\UserInfoDTO;

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

        $user = User::find($userId);

        return $user;
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

    public function addAddress($userId, $addressDTO)
    {
        $address = Address::create([
            'address_line' => $addressDTO->getStreetLine(),
        ]);
        $userAddress = UserAddress::create([
            'user_id' => $userId,
            'address_id' => $address->id,
            'name' => $addressDTO->getName(),
            'phone_number' => $addressDTO->getPhoneNumber(),
            'is_default' => $addressDTO->isDefault(),
        ]);

        // $user = User::find($userId);
        // $user->addresses()->attach($address->id, ['is_default' => $addressDTO->isDefault()]);

        //if isDefault == 1, set all other addresses to isDefault = 0
        if ($addressDTO->isDefault() == 1) {
            UserAddress::where('user_id', $userId)
                ->where('address_id', '!=', $address->id)
                ->update(['is_default' => 0]);
        }

        return $this->true;
    }

    public function makeAddressDefault($userId, $addressId)
    {
        $user = User::find($userId);
        $user->addresses()->updateExistingPivot($addressId, ['is_default' => 1]);
        UserAddress::where('user_id', $user->id)
            ->where('address_id', '!=', $addressId)
            ->update(['is_default' => 0]);
        return $this->true;
    }

    public function updateAddress($userId, $addressDTO)
    {
        $user = User::find($userId);
        Address::where('id', $addressDTO->getAddressId())
            ->update(['address_line' => $addressDTO->getStreetLine()]);

        $user->addresses()->updateExistingPivot($addressDTO->getAddressId(), [
            'name' => $addressDTO->getName(),
            'phone_number' => $addressDTO->getPhoneNumber(),
        ]);
        if($user)
            return $this->true;
        else
            return $this->false;
    }

    public function deleteAddress($userId, $addressId)
    {
        $user = User::find($userId);
        $user->addresses()->detach($addressId);

        if($user) return $this->true;
        else return $this->false;
    }
}
