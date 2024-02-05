<?php

declare(strict_types=1);

use geoquizz\service\web\action\GetGameAction;
use geoquizz\service\web\action\ListGamesAction;

return static function ($app) {

    $app->get('/games[/]', ListGamesAction::class);

    $app->get('/games/{id}[/]', GetGameAction::class);
};
