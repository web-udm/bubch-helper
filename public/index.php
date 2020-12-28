<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, false, false);

$app->get('/test', function (Request $request, Response $response, array $args) {
    $response->getBody()->write('Hello, Slim');

    return $response;
});

$app->run();