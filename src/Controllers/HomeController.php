<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Services\VkApiService;
use Slim\Views\Twig;
use App\Serializers\PostSerializer;

class HomeController
{
    private Twig $twig;

    private VkApiService $vkSender;

    private PostSerializer $postSerializer;

    public function __construct(ContainerInterface $container)
    {
        $this->twig = $container->get('view');
        $this->vkSender = new VkApiService();
        $this->postSerializer = new PostSerializer();
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     *
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

            $posts = [];

            foreach ($links as $link) {
                preg_match('#vk\.com/(.+)#', $link, $match);

                if (empty($match[1])) {
                    throw new \Exception("<p>Косячная ссылка: $link</p>");
                }

                $groupId = trim($match[1]); //убираем символ переноса строки, обусловлено вводом пользователя в форму
                $posts[] = $this->vkSender->getPosts($groupId, 2);
            }

            $serializePosts = $this->postSerializer->serialize($posts);

            echo '<pre>';
            var_dump($serializePosts);
            var_dump($posts);
            echo '</pre>';

            return $this->twig->render($response, 'home/results.twig',['groupsData' => $serializePosts]);
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