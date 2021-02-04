<?php

use App\Controllers\HomeController;

$app->get('/', HomeController::class . ':home');
$app->get('/token', HomeController::class . ':token');
$app->post('/results', HomeController::class . ':results');