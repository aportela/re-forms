<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response, array $args) {
        return $this->get('Twig')->render($response, 'index-quasar.html.twig', []);
    })->add(\ReForms\Middleware\JWT::class);
};
