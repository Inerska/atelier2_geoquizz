<?php

declare(strict_types=1);

namespace geoquizz\auth\domain\exception;

use Exception;

/**
 * Exception for the body content
 */
class MissingBodyContentException extends Exception
{

    /**
     * MissingBodyContentException constructor.
     * @param $message
     * @param $code
     */
    public function __construct($message = "The body content is not valid", $code = 400)
    {
        parent::__construct($message, $code);
    }


}