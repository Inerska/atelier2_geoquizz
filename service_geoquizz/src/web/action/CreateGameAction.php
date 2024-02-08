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
        $data = $request->getBody()->getContents();
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        $serie_id = $data['serie_id'];
        $level_id = $data['level_id'];
        $profile_id = $data['profile_id'];
        $is_public = $data['is_public'] ?? false;

        $photos = $this->httpClient->request(
            'GET',
            'http://gateway_nginx/api/v1/series/' . $serie_id);

        $photos = json_decode($photos->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $photos = $photos['data']['photos'];

        $photosCount = $this->httpClient->request(
            'GET',
            'http://gateway_nginx/api/v1/levels/' . $level_id);

        $photosCount = json_decode($photosCount->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $photosCount = $photosCount['data']['photoCount'];

        $photosForLevel = array_rand($photos, (int)$photosCount);

        $game = new Game();
        $game->setSerieId($serie_id);
        $game->setLevelId(1);
        $game->setPhotos(json_encode($photosForLevel, JSON_THROW_ON_ERROR));
        $game->setIsPublic($is_public);

        try {
            $playedGame = new PlayedGame();

            $profile = $this->entityManager->find(Profile::class, $profile_id);

            if ($profile === null) {
                return $response->withStatus(404);
            }

            $this->entityManager->persist($game);
            $this->entityManager->flush();

            $playedGame->setProfile($profile);

            $playedGame->setStatus(0);
            $playedGame->setGameId($game->getId());
            $playedGame->setScore(-1);
            $playedGame->setAdvancement(-1);
            $playedGame->setTime(-1);
            $playedGame->setDistance(-1);

            $this->entityManager->persist($playedGame);
            $this->entityManager->flush();
        } catch (NotSupported $e) {
            $request->getBody()->write(json_encode(['error' => 'Not supported'], JSON_THROW_ON_ERROR));
            return $response->withStatus(500);
        } catch (ORMException $e) {
            $request->getBody()->write(json_encode(['error' => 'ORM exception'], JSON_THROW_ON_ERROR));
            return $response->withStatus(500);
        }

        $response->getBody()->write(json_encode(['id' => $playedGame->getId()], JSON_THROW_ON_ERROR));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}