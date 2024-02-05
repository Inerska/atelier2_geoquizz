<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
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
        try {
            $game = $this->entityManager->getRepository(Game::class)->find($args['id']);
        } catch (NotSupported $e) {
            $request->getBody()->write(json_encode(['error' => 'Not supported'], JSON_THROW_ON_ERROR));
            return $response->withStatus(500);
        }
        if ($game === null) {
            return $response->withStatus(404);
        }

        $response->getBody()->write(json_encode($game, JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    }
}