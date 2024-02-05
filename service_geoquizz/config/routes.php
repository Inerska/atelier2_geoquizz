<?php

declare(strict_types=1);

return function ($app) {
    $app->get('/hello/{name}', function ($request, $response, $args) {
        $name = $args['name'];
        $response->getBody()->write("Hello, $name");
        return $response;
    });
};
