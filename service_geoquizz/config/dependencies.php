<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

return function ($container) {
    $container->set('logger', function () {
        $logger = new Logger('geoquizz');
        $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
        return $logger;
    });

    $container->set(EntityManager::class, function () {
        $config = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../src/infrastructure/persistence/entity'], true);
        try {
            $connectionConfiguration = parse_ini_file('database.conf.ini');
            $connection = DriverManager::getConnection($connectionConfiguration);

            if ($connection === null) {
                throw new Exception('Connection is null');
            }

            return new EntityManager($connection, $config);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    });

    $container->set('http_client', function () {
        return new Client();
    });
};
