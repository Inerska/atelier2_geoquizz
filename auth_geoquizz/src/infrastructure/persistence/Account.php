<?php

declare(strict_types=1);

namespace geoquizz\auth\infrastructure\persistence;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Class Account
 */
#[Table]
#[Entity]
class Account
{

    /**
     * @var int $id
     */
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column]
    private int $id;

    /**
     * @var string $mail
     */
    #[Column]
    private string $mail;

    /**
     * @var string $password
     */
    #[Column]
    private string $password;

    /**
     * @var string $accessToken
     */
    #[Column]
    private string $accessToken;

    /**
     * @var string $refreshToken
     */
    #[Column]
    private string $refreshToken;

    #[Column]
    private int $profileId;

    public function __construct(string $mail, string $password, int $profileId)
    {
        $this->mail = $mail;
        $this->password = $password;
        $this->profileId = $profileId;
    }

    /**
     * @param $property string
     * @return null | mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    /**
     * @param $property string
     * @param $value mixed
     * @return void
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

}


