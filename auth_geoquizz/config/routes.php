<?php

declare(strict_types=1);

use geoquizz\auth\web\action\LoginAction;
use geoquizz\auth\web\action\RegisterAction;
use geoquizz\auth\web\action\TokenAction;

const BASE_PATH = '/api/v1';

return function ($app) {

    $app->post(BASE_PATH . '/login', LoginAction::class);
    $app->post(BASE_PATH . '/register', RegisterAction::class);
    $app->post(BASE_PATH . '/token', TokenAction::class);

};