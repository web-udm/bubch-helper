<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Services\VkSenderService;
use Slim\App;
use Slim\Views\Twig;

class HomeController
{
    /**
     * @var ContainerInterface
     */
    public ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array $groupsIDs
     * @param int $numberOfPosts
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function results(RequestInterface $request,
                            ResponseInterface $response,
                            array $groupsIDs,
                            int $numberOfPosts) : ResponseInterface
    {
        $sender = new VkSenderService();

        foreach ($groupsIDs as $groupsId) {
            $res = $sender->getPosts($numberOfPosts, $groupsId);
        }
    }

    public function home(RequestInterface $request, ResponseInterface $response) : ResponseInterface
    {


        return $this->container->get('view')->render($response, 'home/home.twig');
    }
}