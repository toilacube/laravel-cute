<?php
namespace App\DTOs\Requests;

class PasswordChangeDTO
{
    public string $oldPassword;
    public string $newPassword;

    public function __construct($oldPassword, $newPassword)
    {
        $this->oldPassword = $oldPassword;
        $this->newPassword = $newPassword;
    }

}
