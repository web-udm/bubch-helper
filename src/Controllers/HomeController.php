<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Services\VkSenderService;

class HomeController
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function home (RequestInterface $request,
                          ResponseInterface $response,
                          array $groupsIDs,
                          int $numberOfPosts) : ResponseInterface
    {
        $sender = new VkSenderService();

        foreach ($groupsIDs as $groupsId) {
            $sender->getPosts($numberOfPosts, $groupsId);
        }
    }
}