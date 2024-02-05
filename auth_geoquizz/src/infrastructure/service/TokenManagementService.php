<?php

declare(strict_types=1);

namespace geoquizz\auth\infrastructure\service;

use geoquizz\auth\domain\service\TokenManagementServiceInterface;

final class TokenManagementService implements TokenManagementServiceInterface
{

    public function refreshToken(string $token): string
    {
        // TODO: Implement refreshToken() method.
        return "dd";
    }
}

