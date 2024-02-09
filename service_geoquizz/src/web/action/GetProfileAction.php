<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use geoquizz\service\domain\dto\ProfileDto;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Game;
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
        try {
            $profile = $this->entityManager->getRepository(Profile::class)->find($args['id']);
        } catch (NotSupported $e) {
            $request->getBody()->write(json_encode(['error' => 'Not supported'], JSON_THROW_ON_ERROR));
            return $response->withStatus(500);
        }
        if ($profile === null) {
            return $response->withStatus(404);
        }

        $dto = new ProfileDto($profile);

        $response->getBody()->write(json_encode($dto, JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    }
}