<?php

namespace App\DTOs\Requests;

class AddAddressDTO
{
    public string $streetLine;
    public string $name;
    public string $phoneNumber;
    public bool $isDefault;

    public function __construct(
        string $streetLine,
        string $name,
        string $phoneNumber,
        bool $isDefault
    ) {
        $this->streetLine = $streetLine;
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
        $this->isDefault = $isDefault;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['streetLine'],
            $data['name'],
            $data['phoneNumber'],
            (bool)$data['isDefault']
        );
    }

    public function toArray(): array
    {
        return [
            'streetLine' => $this->streetLine,
            'name' => $this->name,
            'phoneNumber' => $this->phoneNumber,
            'isDefault' => $this->isDefault,
        ];
    }

    public function getStreetLine(): string
    {
        return $this->streetLine;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }
}
