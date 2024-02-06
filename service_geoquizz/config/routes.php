<?php

declare(strict_types=1);

use geoquizz\service\web\action\CreateGameAction;
use geoquizz\service\web\action\GetGameAction;
use geoquizz\service\web\action\ListGamesAction;
use geoquizz\service\web\action\UpdateGameAction;

return static function ($app) {

    $app->get('/games[/]', ListGamesAction::class);

    $app->get('/games/{id}[/]', GetGameAction::class);

    $app->put('/games/{id}[/]', UpdateGameAction::class);

    $app->post('/games[/]', CreateGameAction::class);
};
