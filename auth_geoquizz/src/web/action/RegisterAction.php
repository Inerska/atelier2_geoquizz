<?php

declare(strict_types=1);

namespace geoquizz\auth\web\action;

use geoquizz\auth\domain\dto\CredentialDTO;
use geoquizz\auth\domain\exception\AuthenticationException;
use geoquizz\auth\domain\exception\MissingBodyContentException;
use geoquizz\auth\domain\service\AuthenticationServiceInterface;
use geoquizz\auth\domain\service\RequestBodyValidatorServiceInterface;
use geoquizz\auth\infrastructure\action\AbstractAction;
use geoquizz\auth\infrastructure\response\ErrorResponseGenerator;
use geoquizz\auth\infrastructure\response\SuccessResponseGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action to register a user
 */
final class RegisterAction extends AbstractAction
{

    /**
     * RegisterAction constructor.
     * @param AuthenticationServiceInterface $authenticationService
     * @param RequestBodyValidatorServiceInterface $requestBodyValidator
     */
    public function __construct(
        private readonly AuthenticationServiceInterface $authenticationService,
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

            $fields_to_validate = ['mail', 'username', 'password', 'confirm_password'];

            $this->requestBodyValidator->validate($body, $fields_to_validate);

            $mail = $body['mail'];
            $username = $body['username'];
            $password = $body['password'];
            $confirm_password = $body['confirm_password'];

            $credential = new CredentialDTO($mail, $password, $confirm_password, $username);

            $data = $this->authenticationService->signUp($credential);
            return SuccessResponseGenerator::generateSuccessResponse($data);
        } catch (MissingBodyContentException|AuthenticationException $e) {
            return ErrorResponseGenerator::generateErrorResponse($e->getCode(), $e->getMessage());
        }
    }
}
