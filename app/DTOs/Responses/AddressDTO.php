<?php

namespace App\DTOs\Responses;

class AddressDTO
{
    public $name;
    public $phoneNumber;
    private $addressId;
    private $streetLine;
    private $isDefault;

    public function __construct($name, $phoneNumber,$addressId,  $streetLine,  $isDefault)
    {
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
        $this->addressId = $addressId;
        $this->streetLine = $streetLine;
        $this->isDefault = $isDefault;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'phoneNumber' => $this->phoneNumber,
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
