<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\Factory\AppFactory;

$container_builder = new ContainerBuilder();

$container = $container_builder->build();

$container->set('logger', function () {
    $logger = new Logger('my_logger');
    $file_handler = new StreamHandler(__DIR__ . '/../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
});

$app = AppFactory::createFromContainer($container);

(require __DIR__ . '/routes.php')($app);

$app->addErrorMiddleware(true, true, true, $container->get('logger'));

return $app;
