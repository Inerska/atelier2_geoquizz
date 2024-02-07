<?php

declare(strict_types=1);

namespace geoquizz\auth\domain\exception;

use Exception;

/**
 * Class for managing token exceptions
 */
class TokenException extends Exception
{

    /**
     * TokenException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }

}