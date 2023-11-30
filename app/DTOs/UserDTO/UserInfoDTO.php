<?php

namespace App\DTOs\UserDTO;

class UserInfoDTO
{
    public  $name;
    public  $username;
    public  $email;
    public  $phoneNumber;
    public  $birthday;
    public  $gender;
    public  $weight;
    public  $height;

    public function __construct(
        $name,
        $username,
        $email,
        $phoneNumber,
        $birthday,
        $gender,
        $weight,
        $height
    ) {
        $this->name = $name;    
        $this->username = $username;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->birthday = $birthday;
        $this->gender = $gender;
        $this->weight = $weight;
        $this->height = $height;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'weight' => $this->weight,
            'height' => $this->height,
        ];
    }
}
