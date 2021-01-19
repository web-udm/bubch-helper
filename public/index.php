<?php

use DI\Container;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Views\TwigMiddleware;

require('../vendor/autoload.php');

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(require('../config/container.php'));

$container = $containerBuilder->build();
AppFactory::setContainer($container);

require('../config/container.php');

$app = AppFactory::create();

//$app->add(TwigMiddleware::createFromContainer($app));
$app->addErrorMiddleware(true, false, false);

require('../config/routes.php');

$app->run();