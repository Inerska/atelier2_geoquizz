<?php

declare(strict_types=1);

namespace geoquizz\auth\domain\service;

use geoquizz\auth\domain\dto\CredentialDTO;
use geoquizz\auth\domain\exception\AuthenticationException;

/**
 * Interface for the authentication service
 */
interface AuthenticationServiceInterface
{

    /**
     * @param CredentialDTO $credentialDTO
     * @return array
     * @throws AuthenticationException
     */
    public function signIn(CredentialDTO $credentialDTO) : array;

    /**
     * @param CredentialDTO $credentialDTO
     * @return array
     * @throws AuthenticationException
     */
    public function signUp(CredentialDTO $credentialDTO) : array;
}