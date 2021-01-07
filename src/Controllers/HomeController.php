<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HomeController
{
    public function home(Request $request, Response $response)
    {
        $response->getBody()->write('Hello, Slim');

        return $response;
    }
}