<?php

declare(strict_types=1);

namespace geoquizz\auth\domain\exception;

use Exception;

/**
 * Class AuthenticationException
 */
class AuthenticationException extends Exception
{

    /**
     * AuthenticationException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }

}