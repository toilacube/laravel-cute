<?php

namespace App\DTOs\Requests;

class UpdateUserDTO
{
    public string $name;
    public string $email;
    public string $phoneNumber;
    public string $birthday;
    public string $gender;
    public int $weight;
    public int $height;

    public function __construct(
        string $name,
        string $email,
        string $phoneNumber,
        string $birthday,
        string $gender,
        int $weight,
        int $height
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->birthday = $birthday;
        $this->gender = $gender;
        $this->weight = $weight;
        $this->height = $height;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['phoneNumber'],
            $data['birthday'],
            $data['gender'],
            $data['weight'],
            $data['height']
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'weight' => $this->weight,
            'height' => $this->height,
        ];
    }
}
