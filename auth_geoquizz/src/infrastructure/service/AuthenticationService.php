<?php

declare(strict_types=1);

namespace geoquizz\auth\infrastructure\service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use geoquizz\auth\domain\dto\CredentialDTO;
use geoquizz\auth\domain\service\AuthenticationServiceInterface;
use geoquizz\auth\domain\service\TokenManagementServiceInterface;
use geoquizz\auth\infrastructure\persistence\Account;
use geoquizz\auth\infrastructure\response\ErrorResponseGenerator;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

final class AuthenticationService implements AuthenticationServiceInterface
{

    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly TokenManagementServiceInterface $tokenManagementService
    ){}

    public function signIn(CredentialDTO $credential): ResponseInterface
    {
        try{
            $account = $this->entityManager->getRepository(Account::class)->findOneBy(['mail' => $credential->getMail()]);
        }catch (NotSupported $e) {
            return ErrorResponseGenerator::generateErrorResponse(500, 'Internal error');
        }

        if($account === null){
            return ErrorResponseGenerator::generateErrorResponse(404, 'Account not found');
        }

        if(password_verify($credential->getPassword(), $account->password)){
            $token = $this->tokenManagementService->refreshToken($account->accessToken);

            $response = new Response();

            $tokenBody = json_encode(['code' => 200, 'token' => $token]);

            $stream = $response->getBody();
            $stream->write($tokenBody);

            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($stream);

        }else{
            return ErrorResponseGenerator::generateErrorResponse(401, 'Invalid password');
        }

    }


    public function signUp()
    {
        //TODO
    }
}