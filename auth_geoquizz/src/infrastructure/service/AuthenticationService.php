<?php

declare(strict_types=1);

namespace geoquizz\auth\infrastructure\service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use geoquizz\auth\domain\dto\CredentialDTO;
use geoquizz\auth\domain\exception\AuthenticationException;
use geoquizz\auth\domain\exception\TokenException;
use geoquizz\auth\domain\service\AuthenticationServiceInterface;
use geoquizz\auth\domain\service\TokenManagementServiceInterface;
use geoquizz\auth\infrastructure\persistence\Account;
use GuzzleHttp\Client;

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
        private readonly TokenManagementServiceInterface $tokenManagementService,
        private readonly Client $client
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
            'refreshToken' => $refreshToken,
            'profileId' => $account->profileId
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

        if ($credential->password != $credential->confirmPassword) {
            throw new AuthenticationException('Les deux mots de passe ne sont pas pareils', 400);
        }
        $data = [
            'username' => $credential->username,
        ];

        $response = $this->client->request('POST', 'http://gateway_nginx/api/v1/profiles/', [
            'body' => json_encode($data)
        ]);

        $json = json_decode($response->getBody()->getContents(), true);
        $profileId = (int)$json['id'];

        $hashedPassword = password_hash($credential->password, PASSWORD_ARGON2ID);
        $account = new Account($credential->mail, $hashedPassword, $profileId);

        $tokens = $this->tokenManagementService->getTokens($account);
        $accessToken = $tokens['accessToken'];
        $refreshToken = $tokens['refreshToken'];

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'profileId' => $profileId
        ];

    }
}