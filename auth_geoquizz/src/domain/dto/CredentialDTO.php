<?php

declare(strict_types=1);

namespace geoquizz\auth\domain\dto;

class CredentialDTO
{

    public function __construct(private string $mail, private string $password)
    {
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

}