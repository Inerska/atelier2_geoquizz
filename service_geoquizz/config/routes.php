<?php

declare(strict_types=1);

use geoquizz\service\web\action\GetGameAction;
use geoquizz\service\web\action\ListGamesAction;

return static function ($app) {
    $app->get('/', function ($request, $response, $args) {
        $response->getBody()->write("Hello, world");
        return $response;
    });
    $app->get('/hello/{name}', function ($request, $response, $args) {
        $name = $args['name'];
        $response->getBody()->write("Hello, $name");
        return $response;
    });

    $app->get('/games', ListGamesAction::class);

    $app->get('/games/{id}', GetGameAction::class);
};
