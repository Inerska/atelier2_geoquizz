<?php

declare(strict_types=1);

namespace geoquizz\auth\web\action;

use geoquizz\auth\domain\service\AuthenticationServiceInterface;
use geoquizz\auth\domain\service\TokenManagementServiceInterface;
use geoquizz\auth\infrastructure\action\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class RegisterAction extends AbstractAction
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

        $body = $request->getBody()->getContents();

        echo $body;

        return $response->withStatus(200);
    }
}
