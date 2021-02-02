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
            $postsNumber = ($request->getParsedBody()['posts_number']);

            if (empty($links[0])) {
                throw new \Exception('<p>Ты забыла записать ссылки</p>');
            }

            if ($postsNumber == 'миллион') {
                throw new \Exception(
                    "<p>Ты сломала сервер</p>" .
                            "<img src='http://ww2.sinaimg.cn/bmiddle/9150e4e5ly1fh3mcehmamg2088088qlf.gif'>"
                );
            }

            $posts = [];

            foreach ($links as $link) {
                preg_match('#vk\.com/(.+)#', $link, $match);

                if (empty($match[1])) {
                    throw new \Exception("<p>Косячная ссылка: $link</p>");
                }

                $groupId = trim($match[1]); //убираем символ переноса строки, обусловлено вводом пользователя в форму
                $posts[] = $this->vkSender->getPosts($groupId, $postsNumber);
            }

            $serializePosts = $this->postSerializer->serialize($posts);

            return $this->twig->render($response, 'home/results.twig',['groupsData' => $serializePosts]);
        } catch (\Exception $e) {
            $response->getBody()->write('<p>Бубчи, что-то пошло не так: </p>' . $e->getMessage());

            return $response;
        }
    }

    public function home(RequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        return $this->twig->render($response, 'home/home.twig');
    }
}