<?php

use App\Controllers\HomeController;

$app->get('/test', HomeController::class . ':home');