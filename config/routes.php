<?php

use App\Controllers\HomeController;

$app->get('/home', HomeController::class . ':home');
$app->get('/results', HomeController::class . ':results');