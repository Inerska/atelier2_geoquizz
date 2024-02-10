<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Game;
use geoquizz\service\infrastructure\persistence\entity\PlayedGame;
use geoquizz\service\infrastructure\persistence\entity\Profile;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CreateGameAction extends AbstractAction
{
    public function __construct(
        private readonly EntityManager $entityManager,
        private readonly Client $httpClient)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $serie_id = $data['serie_id'];
        $level_id = $data['level_id'];
        $profile_id = $data['profile_id'];
        $is_public = $data['is_public'] ?? false;

        $existingGame = $this->entityManager->getRepository(Game::class)->findOneBy([
            'serie_id' => $serie_id,
            'level_id' => $level_id,
        ]);

        if ($existingGame === null) {
            $photos = $this->fetchPhotosForGame($serie_id, $level_id);

            $existingGame = new Game();
            $existingGame->setSerieId($serie_id);
            $existingGame->setLevelId($level_id);
            $existingGame->setPhotos(json_encode($photos, JSON_THROW_ON_ERROR));
            $existingGame->setIsPublic($is_public);

            $this->entityManager->persist($existingGame);
            $this->entityManager->flush();

        }

        $playedGame = new PlayedGame();
        $profile = $this->entityManager->find(Profile::class, $profile_id);
        if ($profile === null) {
            return $response->withStatus(404, "Profile not found");
        }

        $playedGame->setGame($existingGame);
        $playedGame->setProfile($profile);
        $playedGame->setStatus(0);
        $playedGame->setScore(0);
        $playedGame->setAdvancement(1);
        $playedGame->setTime(0);
        $playedGame->setDistance(0);

        $this->entityManager->persist($playedGame);
        $this->entityManager->flush();

        $responseBody = json_encode(['id' => $playedGame->getId()], JSON_THROW_ON_ERROR);
        $response->getBody()->write($responseBody);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    private function fetchPhotosForGame(int $serie_id, int $level_id): array
    {
        $photosResponse = $this->httpClient->request('GET', 'http://gateway_nginx/api/v1/series/' . $serie_id);
        $photosData = json_decode($photosResponse->getBody()->getContents(), true);
        $photos = $photosData['data']['photos'] ?? [];

        $photosCountResponse = $this->httpClient->request('GET', 'http://gateway_nginx/api/v1/levels/' . $level_id);
        $photosCountData = json_decode($photosCountResponse->getBody()->getContents(), true);
        $photosCount = $photosCountData['data']['photoCount'] ?? count($photos);

        shuffle($photos);
        return array_slice($photos, 0, $photosCount);
    }
}
