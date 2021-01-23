<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Services\VkSenderService;
use Slim\Views\Twig;

class HomeController
{
    private Twig $twig;

    private VkSenderService $vkSender;

    public function __construct(ContainerInterface $container)
    {
        $this->twig = $container->get('view');
        $this->vkSender = new VkSenderService();
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
                            ResponseInterface $response) : ResponseInterface
    {
        try {
            $links = explode("\n", $request->getParsedBody()['links']);

            if (empty($links[0])) {
                throw new \Exception('<p>Ты забыла записать ссылки</p>');
            }

            foreach ($links as $link) {
                preg_match('#vk\.com/(.+)#', $link, $match);
                $groupId = $match[1];

                $post = $this->vkSender->getPosts(1, $groupId);

                var_dump($post);
            }

        } catch (\Exception $e) {
            $response->getBody()->write('<p>Бубчи, что-то пошло не так :C: </p>' . $e->getMessage());

            return $response;
        }
    }

    public function home(RequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        return $this->twig->render($response, 'home/home.twig');
    }
}