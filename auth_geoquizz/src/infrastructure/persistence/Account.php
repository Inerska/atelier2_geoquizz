<?php

declare(strict_types=1);

namespace geoquizz\auth\infrastructure\persistence;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Table]
#[Entity]
class Account
{

    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column]
    private int $id;

    #[Column]
    private string $mail;

    #[Column]
    private string $password;

    #[Column]
    private string $accessToken;

    #[Column]
    private string $refreshToken;

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

}


