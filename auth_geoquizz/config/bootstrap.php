<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\Factory\AppFactory;

$container_builder = new ContainerBuilder();

$container = $container_builder->build();

(require_once __DIR__ . '/dependencies.php')($container);

$app = AppFactory::createFromContainer($container);

(require_once __DIR__ . '/routes.php')($app);
(require_once __DIR__ . '/doctrine.php')($container);

$app->addErrorMiddleware(true, true, true, $container->get('logger'));

return $app;