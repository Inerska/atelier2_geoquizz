<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use Exception;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Game;
use geoquizz\service\infrastructure\persistence\entity\Profile;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CreateProfileAction extends AbstractAction
{

    public function __construct(
        private readonly EntityManager $entityManager)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $data = $request->getBody()->getContents();
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        $profile = new Profile();
        $profile->setUsername($data['username']);

        $profile->setActualGameId(null);

        try {
            $this->entityManager->persist($profile);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()], JSON_THROW_ON_ERROR));
            return $response->withStatus(500);
        }

        $response->getBody()->write(json_encode(['id' => $profile->getId()], JSON_THROW_ON_ERROR));
        return $response
            ->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }
}