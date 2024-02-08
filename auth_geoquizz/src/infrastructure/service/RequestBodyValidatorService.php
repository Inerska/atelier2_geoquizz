<?php

namespace geoquizz\auth\infrastructure\service;

use geoquizz\auth\domain\exception\MissingBodyContentException;
use geoquizz\auth\domain\service\RequestBodyValidatorServiceInterface;

/**
 * Class RequestBodyValidatorService
 */
class RequestBodyValidatorService implements RequestBodyValidatorServiceInterface
{

    /**
     * Validate the body content
     * @throws MissingBodyContentException
     */
    public function validate($body, $fields) : void
    {
        foreach ($fields as $field) {
            if (!isset($body[$field])) {
                throw new MissingBodyContentException($field . ' is required');
            }
        }
    }

}