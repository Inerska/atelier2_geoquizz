<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Game;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CreateGameAction extends AbstractAction
{

    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $raw = $request->getBody()->getContents();
        $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

        //TODO: impl
    }
}