<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use geoquizz\service\domain\dto\GameDto;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Game;
use geoquizz\service\infrastructure\persistence\entity\PlayedGame;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class GetGameAction extends AbstractAction
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $game = $this->entityManager->find(Game::class, $args['id']);

        if ($game === null) {
            $response->getBody()->write(json_encode(['error' => 'Game not found'], JSON_THROW_ON_ERROR));
            return $response->withStatus(404);
        }

        $gameDto = new GameDto($game);

        /** @var PlayedGame $playedGame */
        $playedGame = $this->entityManager
            ->getRepository(PlayedGame::class)
            ->findBy(['game_id' => $game->getId()]);

        if ($playedGame === null) {
            $response->getBody()->write(json_encode(['error' => 'PlayedGame not found'], JSON_THROW_ON_ERROR));
            return $response->withStatus(404);
        }

        $playedGame = $playedGame[0];

        $returnDto = [
            'game' => $gameDto,
            'advancement' => $playedGame->getAdvancement()
        ];

        $response->getBody()->write(json_encode($returnDto, JSON_THROW_ON_ERROR));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}