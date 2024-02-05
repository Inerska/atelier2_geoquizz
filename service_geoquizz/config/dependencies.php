<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

return function ($container) {
    $container->set('logger', function () {
        $logger = new Logger('my_logger');
        $file_handler = new StreamHandler(__DIR__ . '/../logs/app.log');
        $logger->pushHandler($file_handler);
        return $logger;
    });

    $container->set('em', function () {
        $config = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../src/infrastructure/entity'], true);
        try {
            $connectionConfiguration = parse_ini_file('database.conf.ini');
            $connection = DriverManager::getConnection($connectionConfiguration);

            if ($connection === null) {
                throw new Exception('Connection is null');
            }

            return new EntityManager($connection, $connectionConfiguration);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    });
};
