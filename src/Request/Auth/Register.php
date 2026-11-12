<?php

namespace App\Request\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class Register
{
    #[Assert\NotBlank(message: "email.not_blank")]
    #[Assert\Email(message: "email.format")]
    public string $email;

    #[Assert\NotBlank(message: "password.not_blank")]
    #[Assert\Type("string", "password.string")]
    public string $password;
}
