<?php

declare(strict_types=1);

use geoquizz\service\web\action\CreateGameAction;
use geoquizz\service\web\action\CreateProfileAction;
use geoquizz\service\web\action\GetGameAction;
use geoquizz\service\web\action\GetProfileAction;
use geoquizz\service\web\action\ListGamesAction;
use geoquizz\service\web\action\ListPlayedGamesAction;
use geoquizz\service\web\action\UpdateGameAction;
use geoquizz\service\web\action\UpdateProfileAction;

return static function ($app) {

    $app->get('/games[/]', ListGamesAction::class);

    $app->get('/games/{id}[/]', GetGameAction::class);
  
    $app->put('/games/{id}[/]', UpdateGameAction::class);

    $app->post('/games[/]', CreateGameAction::class);

    $app->get('/profiles/{id}[/]', GetProfileAction::class);

    $app->post('/profiles[/]', CreateProfileAction::class);

    $app->put('/profiles/{id}[/]', UpdateProfileAction::class);

    $app->get('/profiles/{id}/playedGames[/]', ListPlayedGamesAction::class);
};
