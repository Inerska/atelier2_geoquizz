<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\ORM\Tools\SchemaTool;
use Slim\Factory\AppFactory;

$container_builder = new ContainerBuilder();

$container = $container_builder->build();

(require __DIR__ . '/dependencies.php')($container);

$app = AppFactory::createFromContainer($container);

(require __DIR__ . '/routes.php')($app);

// Create the tables
(require __DIR__ . '/doctrine.php')($container);

$app->addErrorMiddleware(true, true, true, $container->get('logger'));

return $app;
