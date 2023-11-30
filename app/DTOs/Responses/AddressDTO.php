<?php

namespace App\DTOs\Responses;

class AddressDTO
{
    private $addressId;
    private $streetLine;
    private $isDefault;

    public function __construct($addressId,  $streetLine,  $isDefault)
    {
        $this->addressId = $addressId;
        $this->streetLine = $streetLine;
        $this->isDefault = $isDefault;
    }

    public function toArray()
    {
        return [
            'addressId' => $this->addressId,
            'streetLine' => $this->streetLine,
            'isDefault' => $this->isDefault,
        ];
    }

    public function getAddressId()
    {
        return $this->addressId;
    }

    public function getStreetLine()
    {
        return $this->streetLine;
    }

    public function isDefault()
    {
        return $this->isDefault;
    }
}
