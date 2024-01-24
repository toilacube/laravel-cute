<?php

namespace App\DTOs\Requests;

class RegisterDTO
{
    public $email;
    public $password;
    public $role;


    public function __construct(string $email, string $password, $role)
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return $this->role;
    }
}
