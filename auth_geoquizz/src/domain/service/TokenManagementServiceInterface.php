<?php

declare(strict_types=1);

namespace geoquizz\auth\domain\service;

use geoquizz\auth\domain\exception\TokenException;

/**
 * Interface for the token management service
 */
interface TokenManagementServiceInterface
{
    /**
     * @param String $refreshToken
     * @return array
     * @throws TokenException
     */
    public function refreshToken(String $refreshToken): array;

}
