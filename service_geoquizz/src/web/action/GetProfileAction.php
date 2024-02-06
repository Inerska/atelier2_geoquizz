<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Profile;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class GetProfileAction extends AbstractAction
{

    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    /**
     * @throws NotSupported
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $id = $args['id'];
        $profile = $this->entityManager->getRepository(Profile::class)->find($id);

        if ($profile === null) {
            $request->getBody()->write(json_encode(['error' => 'Profile not found'], JSON_THROW_ON_ERROR));
            return $response->withStatus(404);
        }

        $request->getBody()->write(json_encode($profile, JSON_THROW_ON_ERROR));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}