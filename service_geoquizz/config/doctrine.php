<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

return function ($container) {
    $entityManager = $container->get(EntityManager::class);
    $schemaTool = new SchemaTool($entityManager);
    $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
    $schemaTool->updateSchema($metadata, true);
};
