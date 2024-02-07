<?php

declare(strict_types=1);

namespace geoquizz\auth\web\action;

use geoquizz\auth\domain\exception\MissingBodyContentException;
use geoquizz\auth\domain\exception\TokenException;
use geoquizz\auth\domain\service\RequestBodyValidatorServiceInterface;
use geoquizz\auth\domain\service\TokenManagementServiceInterface;
use geoquizz\auth\infrastructure\action\AbstractAction;
use geoquizz\auth\infrastructure\response\ErrorResponseGenerator;
use geoquizz\auth\infrastructure\response\SuccessResponseGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action to refresh a token
 */
final class TokenAction extends AbstractAction
{

    /**
     * TokenAction constructor.
     * @param TokenManagementServiceInterface $tokenManagementService
     * @param RequestBodyValidatorServiceInterface $requestBodyValidator
     */
    public function __construct(
        private readonly TokenManagementServiceInterface $tokenManagementService,
        private readonly RequestBodyValidatorServiceInterface $requestBodyValidator
    ){}


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        try {
            $jsonBody = $request->getBody()->getContents();
            $body = json_decode($jsonBody, true);

            $fields_to_validate = ['refresh_token'];

            $this->requestBodyValidator->validate($body, $fields_to_validate);

            $refresh_token = $body['refresh_token'];

            $data = $this->tokenManagementService->refreshToken($refresh_token);

            return SuccessResponseGenerator::generateSuccessResponse($data);
        } catch (TokenException|MissingBodyContentException $e) {
            return ErrorResponseGenerator::generateErrorResponse($e->getCode(), $e->getMessage());
        }
    }



}