<?php

namespace geoquizz\auth\infrastructure\response;

use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

/**
 * Class for generating success responses
 */
class SuccessResponseGenerator
{

    /**
     * Generate a success response
     * @param array $bodyContent
     * @return ResponseInterface
     */
    public static function generateSuccessResponse(array $bodyContent): ResponseInterface
    {
        $response = new Response();

        $responseData = ['code' => 200];

        foreach ($bodyContent as $key => $value) {
            $responseData[$key] = $value;
        }

        $errorBody = json_encode($responseData);

        $stream = $response->getBody();
        $stream->write($errorBody);

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
    }


}