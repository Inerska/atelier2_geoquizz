<?php

declare(strict_types=1);

use Doctrine\ORM\Tools\SchemaTool;

return function ($container) {
    $entityManager = $container->get('em');

    $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

    $schemaTool = new SchemaTool($entityManager);

    $schemaTool->updateSchema($metadata);

};
