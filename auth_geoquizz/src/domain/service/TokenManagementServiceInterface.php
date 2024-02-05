<?php

declare(strict_types=1);

namespace geoquizz\auth\domain\service;

interface TokenManagementServiceInterface
{
    public function refreshToken(String $token): String;

}
