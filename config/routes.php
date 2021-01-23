<?php

use App\Controllers\HomeController;

$app->get('/home', HomeController::class . ':home');
$app->post('/results', HomeController::class . ':results');