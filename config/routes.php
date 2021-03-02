<?php

use App\Controllers\HomeController;

$app->get('/', HomeController::class . ':homeAction');
$app->get('/token', HomeController::class . ':tokenAction');
$app->post('/results', HomeController::class . ':resultsAction');