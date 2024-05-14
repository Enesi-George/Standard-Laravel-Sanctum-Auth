<?php
namespace App\DataTransferObject;

use Spatie\DataTransferObject\DataTransferObject;

class StoreUserDTO extends DataTransferObject
{
    public string $username;

    public string $first_name;

    public string $last_name;

    public string $email;

    public string $phone_number;

    public string $password;
}