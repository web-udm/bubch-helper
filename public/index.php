<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;

require('../vendor/autoload.php');

$app = AppFactory::create();

$app->addErrorMiddleware(true, false, false);

require('../config/routes.php');

$app->run();