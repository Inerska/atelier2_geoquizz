<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use geoquizz\service\domain\dto\PlayedGameDto;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\PlayedGame;
use geoquizz\service\infrastructure\persistence\entity\Profile;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ListPlayedGamesAction extends AbstractAction
{
    public function __construct(
        private readonly EntityManager $entityManager)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $profileId = $args['profileId'];

        if (empty($profileId)) {
            $response->getBody()->write(json_encode(['error' => 'profileId is required']));
            return $response
                ->withStatus(400);
        }

        $profile = $this->entityManager->find(Profile::class, $profileId);
        if (!$profile) {
            $response->getBody()->write(json_encode(['error' => 'Profile not found']));
            return $response->withStatus(404);
        }

        $playedGames = $this->entityManager->getRepository(PlayedGame::class)->findBy(['profile' => $profileId]);

        $playedGamesDto = array_map(function ($playedGame) {
            return new PlayedGameDto($playedGame);
        }, $playedGames);

        $response->getBody()->write(json_encode($playedGamesDto));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
