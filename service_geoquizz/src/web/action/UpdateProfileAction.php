<?php

declare(strict_types=1);

namespace geoquizz\service\web\action;

use Doctrine\ORM\EntityManager;
use geoquizz\service\infrastructure\action\AbstractAction;
use geoquizz\service\infrastructure\persistence\entity\Profile;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateProfileAction extends AbstractAction
{
    public function __construct(private readonly EntityManager $entityManager)
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args): ResponseInterface
    {
        $id = $args['id'];
        $profile = $this->entityManager->getRepository(Profile::class)->find($id);

        if ($profile === null) {
            $request->getBody()->write(json_encode(['error' => 'Profile not found'], JSON_THROW_ON_ERROR));
            return $response->withStatus(404);
        }

        $data = json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $profile->setUsername($data['username']);
        $profile->setEmail($data['email']);
        $profile->setFirstname($data['firstname']);
        $profile->setLastname($data['lastname']);

        $this->entityManager->persist($profile);
        $this->entityManager->flush();

        $request->getBody()->write(json_encode($profile, JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}