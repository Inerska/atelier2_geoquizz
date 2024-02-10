<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Game;
use geoquizz\service\infrastructure\persistence\entity\PlayedGame;
use geoquizz\service\infrastructure\persistence\entity\Profile;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ListPlayedGamesAction extends AbstractAction
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly Client $client)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $profileId = $args['id'];
        $profile = $this->entityManager->find(Profile::class, $profileId);

        if (!$profile) {
            $response->getBody()->write(json_encode(['error' => 'Profile not found']));
            return $response->withStatus(404);
        }

        $playedGames = $this->entityManager->getRepository(PlayedGame::class)->findBy(['profile' => $profile]);

        $playedGamesData = [];
        foreach ($playedGames as $playedGame) {

            $game = $playedGame->getGame();

            $photo = json_decode($game->getPhotos(), false, 512, JSON_THROW_ON_ERROR);
            $photo = $photo[0];

            $requestSeries = $this->client->request('GET', 'http://gateway_nginx/api/v1/series/' . $game->getSerieId());
            $series = json_decode($requestSeries->getBody()->getContents(), true);

            $city = $series['data']['city'];

            $requestLevel = $this->client->request('GET', 'http://gateway_nginx/api/v1/levels/' . $game->getLevelId());
            $level = json_decode($requestLevel->getBody()->getContents(), true);
            $level = $level['data']['title'];

            $playedGamesData[] = [
                'playedGamesId' => $playedGame->getId(),
                'image' => $photo,
                'city' => $city,
                'level' => $level,
                'score' => $playedGame->getScore(),
                'status' => $playedGame->getStatus(),
            ];
        }

        $response->getBody()->write(json_encode($playedGamesData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
