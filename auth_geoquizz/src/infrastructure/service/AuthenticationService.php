<?php

declare(strict_types=1);

namespace geoquizz\auth\infrastructure\service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use geoquizz\auth\domain\dto\CredentialDTO;
use geoquizz\auth\domain\exception\AuthenticationException;
use geoquizz\auth\domain\exception\TokenException;
use geoquizz\auth\domain\service\AuthenticationServiceInterface;
use geoquizz\auth\domain\service\TokenManagementServiceInterface;
use geoquizz\auth\infrastructure\persistence\Account;

/**
 * Class for managing the authentication
 */
final class AuthenticationService implements AuthenticationServiceInterface
{

    /**
     * AuthenticationService constructor.
     * @param EntityManager $entityManager
     * @param TokenManagementServiceInterface $tokenManagementService
     */
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly TokenManagementServiceInterface $tokenManagementService
    ){}



    /**
     * Sign in the user
     * @param CredentialDTO $credential
     * @return array
     * @throws AuthenticationException|TokenException
     */
    public function signIn(CredentialDTO $credential): array
    {
        try{
            $account = $this->entityManager->getRepository(Account::class)->findOneBy(['mail' => $credential->mail]);
        }catch (NotSupported) {
            throw new AuthenticationException('Internal error', 500);
        }

        if($account === null){
            throw new AuthenticationException('Account not found', 404);
        }

        if(!password_verify($credential->password, $account->password)) {
            throw new AuthenticationException('Invalid password', 401);
        }

        /** @var Account|null $account */
        $tokens = $this->tokenManagementService->getTokens($account);
        $accessToken = $tokens['accessToken'];
        $refreshToken = $tokens['refreshToken'];

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];
    }



    /**
     * Sign up the user
     * @param CredentialDTO $credential
     * @return array
     * @throws AuthenticationException|TokenException
     */
    public function signUp(CredentialDTO $credential) : array
    {
        try{
            $account = $this->entityManager->getRepository(Account::class)->findOneBy(['mail' => $credential->mail]);
        }catch (NotSupported) {
            throw new AuthenticationException('Internal error', 500);
        }

        if ($account !== null) {
            throw new AuthenticationException('Account with this mail already exist', 409);
        }

        $hashedPassword = password_hash($credential->password, PASSWORD_ARGON2ID);
        $account = new Account($credential->mail, $hashedPassword);

        $tokens = $this->tokenManagementService->getTokens($account);
        $accessToken = $tokens['accessToken'];
        $refreshToken = $tokens['refreshToken'];

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];

    }
}