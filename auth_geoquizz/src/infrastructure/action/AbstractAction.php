<?php

declare(strict_types=1);

namespace geoquizz\auth\infrastructure\action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Base class for all actions.
 */
abstract class AbstractAction
{
    /**
     * Entry point for all actions.
     *
     * @param ServerRequestInterface $request Request object
     * @param ResponseInterface $response Response object
     * @param array $args Route parameters
     * @return ResponseInterface Response object
     */
    abstract public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface;
}