<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use geoquizz\auth\domain\service\AuthenticationServiceInterface;
use geoquizz\auth\domain\service\RequestBodyValidatorServiceInterface;
use geoquizz\auth\domain\service\TokenManagementServiceInterface;
use geoquizz\auth\infrastructure\service\AuthenticationService;
use geoquizz\auth\infrastructure\service\RequestBodyValidatorService;
use geoquizz\auth\infrastructure\service\TokenManagementService;
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
        $config = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../src'], true);
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

    $container->set(AuthenticationServiceInterface::class, function () use ($container) {
        return new AuthenticationService(
            $container->get('em'),
            $container->get(TokenManagementServiceInterface::class)
        );
    });

    $container->set(RequestBodyValidatorServiceInterface::class, function () use ($container) {
        return new RequestBodyValidatorService();
    });

    $container->set(TokenManagementServiceInterface::class, function () use ($container){
        return new TokenManagementService($container->get('em'));
    });


};