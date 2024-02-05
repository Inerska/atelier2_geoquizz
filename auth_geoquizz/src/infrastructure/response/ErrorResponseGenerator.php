<?php

namespace geoquizz\auth\infrastructure\response;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class ErrorResponseGenerator
{
    public static function generateErrorResponse(int $statusCode, string $message): ResponseInterface
    {
        $response = new Response();

        $errorBody = json_encode(['code' => $statusCode, 'message' => $message]);

        $stream = $response->getBody();
        $stream->write($errorBody);

        return $response
            ->withStatus($statusCode)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
    }
}