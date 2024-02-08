<?php

declare(strict_types=1);

use geoquizz\service\web\action\CreateGameAction;
use geoquizz\service\web\action\GetGameAction;
use geoquizz\service\web\action\GetProfileAction;
use geoquizz\service\web\action\ListGamesAction;
use geoquizz\service\web\action\UpdateGameAction;
use geoquizz\service\web\action\UpdateProfileAction;

return static function ($app) {

    // hello world
    $app->get('/api/v1/hello/{name}', function ($request, $response, $args) {
        $name = $args['name'];
        $response->getBody()->write("Hello, $name");
        return $response;
    });

    $app->get('/games[/]', ListGamesAction::class);

    $app->get('/games/{id}[/]', GetGameAction::class);
  
    $app->put('/games/{id}[/]', UpdateGameAction::class);

    $app->post('/games[/]', CreateGameAction::class);

    $app->get('/profiles[/]', GetProfileAction::class);

    $app->put('/profiles/{id}[/]', UpdateProfileAction::class);
};
