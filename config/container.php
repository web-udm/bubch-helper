<?php

use Psr\Container\ContainerInterface;
use Slim\Views\Twig;

return [
    'settings' => function() {
        return require('settings.php');
    },

    'view' => function(ContainerInterface $container) {
        if ($container->get('settings')['production']) {
            return Twig::create('../templates',  ['cache' => '../cache']);
        };

        return Twig::create('../templates');
    },
];