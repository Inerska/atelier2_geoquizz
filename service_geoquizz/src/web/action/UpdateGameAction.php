<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use Exception;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\PlayedGame;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UpdatePlayedGameAction extends AbstractAction
{
    public function __construct(
        private readonly EntityManager $entityManager)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $playedGameId = $args['id'];
        $playedGame = $this->entityManager->find(PlayedGame::class, $playedGameId);

        if (!$playedGame) {
            $response->getBody()->write(json_encode(['error' => 'PlayedGame not found'], JSON_THROW_ON_ERROR));
            return $response->withStatus(404);
        }

        $data = json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (isset($data['status'])) {
            $playedGame->setStatus($data['status']);
        }
        if (isset($data['time'])) {
            $playedGame->setTime($data['time']);
        }
        if (isset($data['score'])) {
            $playedGame->setScore($data['score']);
        }
        if (isset($data['advancement'])) {
            $playedGame->setAdvancement($data['advancement']);
        }

        try {
            $this->entityManager->flush();
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Could not update PlayedGame'], JSON_THROW_ON_ERROR));
            return $response->withStatus(500);
        }

        $response->getBody()->write(json_encode(['success' => 'PlayedGame updated successfully'], JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
