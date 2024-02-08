<?php

declare(strict_types=1);

namespace geoquizz\auth\domain\dto;

/**
 * DTO class for representing credentials for connection
 */
class CredentialDTO
{

    /**
     * @var string $mail
     */
    private string $mail;
    /**
     * @var string $username
     */
    private string $username;
    /**
     * @var string $password
     */
    private string $password;
    /**
     * @var string $confirmPassword
     */
    private string $confirmPassword;

    /**
     * CredentialDTO constructor.
     * @param string $mail
     * @param string $password
     * @param string $confirmPassword
     */
    public function __construct(string $mail, string $password, string $confirmPassword = "", string $username = "")
    {
        $this->mail = $mail;
        $this->username = $username;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
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


}