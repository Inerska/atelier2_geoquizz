<?php

declare(strict_types=1);

namespace geoquizz\auth\domain\service;

use geoquizz\auth\domain\dto\CredentialDTO;

interface AuthenticationServiceInterface
{
    public function signIn(CredentialDTO $credentialDTO);
    public function signUp();
}