<?php

use App\Controllers\HomeController;

$app->get('/', HomeController::class . ':home');
$app->post('/results', HomeController::class . ':results');