<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Game;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ListGamesAction extends AbstractAction
{

    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        try {
            $gameRepository = $this->entityManager->getRepository(Game::class);
        } catch (NotSupported $e) {
            $response->getBody()->write(json_encode(['error' => 'An error occured']));
            return $response->withStatus(500);
        }
        $games = $gameRepository->findAll();

        $response->getBody()->write(json_encode($games));
        return $response;
    }
}