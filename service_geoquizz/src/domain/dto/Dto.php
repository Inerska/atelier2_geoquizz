<?php

declare(strict_types=1);

namespace geoquizz\service\domain\dto;

use JsonException;

/*
 * Base class for DTOs
 */

class Dto
{
    /**
     * @throws JsonException if an error occurs
     */
    final public function toJson(): string
    {
        return json_encode(get_object_vars($this), JSON_THROW_ON_ERROR);
    }
}