<?php

namespace App\DTOs\Requests;

class UpdateAddressDTO
{
    public int $addressId;
    public ?string $streetLine;
    public ?string $name;
    public ?string $phoneNumber;

    public function __construct(
        int $addressId,
        ?string $streetLine = null,
        ?string $name = null,
        ?string $phoneNumber = null
    ) {
        $this->addressId = $addressId;
        $this->streetLine = $streetLine;
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['addressId'],
            $data['streetLine'] ?? null,
            $data['name'] ?? null,
            $data['phoneNumber'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'addressId' => $this->addressId,
            'streetLine' => $this->streetLine,
            'name' => $this->name,
            'phoneNumber' => $this->phoneNumber,
        ];
    }

    public function getAddressId(): int
    {
        return $this->addressId;
    }

    public function getStreetLine(): ?string
    {
        return $this->streetLine;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }
}
