<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use geoquizz\service\domain\dto\GameDto;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Game;
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

        // get the advancement of playedgame
        $playedGame = $game->getPlayedGame();

        // make new json of dto appending the advancement
        $returnDto = [
            'game' => $gameDto,
            'advancement' => $playedGame->getAdvancement()
        ];

        $response->getBody()->write(json_encode($returnDto, JSON_THROW_ON_ERROR));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}