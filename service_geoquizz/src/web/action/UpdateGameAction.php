<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use Exception;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Game;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateGameAction extends AbstractAction
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly Client $client)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $gameId = $args['id'];
        $game = $this->entityManager->find(Game::class, $gameId);

        if (!$game) {
            $response->getBody()->write(json_encode(['error' => 'Game not found'], JSON_THROW_ON_ERROR));
            return $response->withStatus(404);
        }

        $data = json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $levelChanged = isset($data['level_id']) && $data['level_id'] != $game->getLevelId();

        $game->setSerieId($data['serie_id'] ?? $game->getSerieId());
        $game->setLevelId($data['level_id'] ?? $game->getLevelId());
        $game->setIsPublic($data['is_public'] ?? $game->isPublic());

        if ($levelChanged) {
            $newLevelId = $data['level_id'];

            $photosCountResponse = $this->client->request(
                'GET',
                "http://gateway_nginx/api/v1/levels/$newLevelId"
            );
            $photosCountData = json_decode($photosCountResponse->getBody()->getContents(), true);
            $photosCount = $photosCountData['data']['photoCount'] ?? 0;

            $photosResponse = $this->client->request(
                'GET',
                "http://gateway_nginx/api/v1/series/{$game->getSerieId()}"
            );

            $photosData = json_decode($photosResponse->getBody()->getContents(), true);

            $photos = $photosData['data']['photos'] ?? [];

            shuffle($photos);

            $newPhotos = array_slice($photos, 0, (int)$photosCount);

            $game->setPhotos(json_encode($newPhotos));
            $game->setLevelId($newLevelId);
        }

        try {
            $this->entityManager->flush();
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Could not update game'], JSON_THROW_ON_ERROR));
            return $response->withStatus(500);
        }

        $response->getBody()->write(json_encode(['success' => 'Game updated successfully'], JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
