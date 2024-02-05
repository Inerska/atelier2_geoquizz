<?php

declare(strict_types=1);

namespace geoquizz\service\infrastructure\action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractAction
{
    abstract public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface;
}