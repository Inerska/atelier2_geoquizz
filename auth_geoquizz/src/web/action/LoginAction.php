<?php

declare(strict_types=1);

namespace geoquizz\auth\web\action;

use geoquizz\auth\domain\dto\CredentialDTO;
use geoquizz\auth\domain\service\AuthenticationServiceInterface;
use geoquizz\auth\domain\service\TokenManagementServiceInterface;
use geoquizz\auth\infrastructure\action\AbstractAction;
use geoquizz\auth\infrastructure\response\ErrorResponseGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class LoginAction extends AbstractAction
{

    public function __construct(
        private readonly AuthenticationServiceInterface $authenticationService,
        private readonly TokenManagementServiceInterface $tokenManagementService
    ){}


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $jsonBody = $request->getBody()->getContents();
        $body = json_decode($jsonBody, true);

        $mail = $body['mail'] ?? null;
        if($mail == null){
            return ErrorResponseGenerator::generateErrorResponse(400, 'Mail is required');
        }

        $password = $body['password'] ?? null;
        if ($password === null) {
            return ErrorResponseGenerator::generateErrorResponse(400, 'Password is required');
        }

        $credential = new CredentialDTO($mail, $password);
        return $this->authenticationService->signIn($credential);

    }

}
