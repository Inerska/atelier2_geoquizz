<?php

declare(strict_types=1);

namespace geoquizz\auth\infrastructure\service;

use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Dotenv\Dotenv;
use Exception;
use Firebase\JWT\JWT;
use geoquizz\auth\domain\exception\TokenException;
use geoquizz\auth\domain\service\TokenManagementServiceInterface;
use geoquizz\auth\infrastructure\persistence\Account;

/**
 * Class for managing tokens with JWT
 */
final class TokenManagementService implements TokenManagementServiceInterface
{

    /**
     * @var string $secretKey
     */
    private string $secretKey;

    /**
     * TokenManagementService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(
        private readonly EntityManager $entityManager
    )
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../config', 'jwt.env');
        $dotenv->load();

        $this->secretKey = $_ENV['JWT_SECRET'];
    }


    /**
     * Get the accessToken and the refreshToken
     * @param Account $account
     * @return array
     * @throws TokenException
     */
    public function getTokens(Account $account): array
    {
        try {
            $issuedAt = new DateTimeImmutable();
            $expire = $issuedAt->modify('+6 minutes')->getTimestamp();

            $data = [
                'exp' => $expire,
                "mail" => $account->mail,
            ];

            $accessToken = JWT::encode($data, $this->secretKey, 'HS512');
            $refreshToken = bin2hex(random_bytes(64));

            $account->accessToken = $accessToken;
            $account->refreshToken = $refreshToken;

            $this->entityManager->persist($account);
            $this->entityManager->flush();

            return ["accessToken" => $accessToken, "refreshToken" => $refreshToken];
        }catch (Exception){
            throw new TokenException("Error while generating tokens", 500);
        }
    }

    /**
     * @param string $refreshToken
     * @return array
     * @throws TokenException
     */
    public function refreshToken(string $refreshToken): array
    {
        try {
            /** @var Account|null $account */
            $account = $this->entityManager->getRepository(Account::class)->findOneBy(["refreshToken" => $refreshToken]);

            if ($account) {
                return $this->getTokens($account);
            }else{
                throw new TokenException("Invalid refresh token", 401);
            }

        }catch (NotSupported){
            throw new TokenException("Error while refreshing token", 500);
        }
    }
}

