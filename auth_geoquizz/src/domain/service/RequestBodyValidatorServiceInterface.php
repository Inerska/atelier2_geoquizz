<?php

declare(strict_types=1);

namespace geoquizz\auth\domain\service;

use geoquizz\auth\domain\exception\MissingBodyContentException;

/**
 * Interface for the request body validator
 */
interface RequestBodyValidatorServiceInterface
{

    /**
     * validate the body content
     * @param $body
     * @param $fields
     * @return void
     * @throws MissingBodyContentException
     */
    public function validate($body, $fields) : void;

}