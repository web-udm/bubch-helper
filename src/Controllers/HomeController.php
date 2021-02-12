<?php

namespace App\Controllers;

use GuzzleHttp\Client;
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
        $this->client = new Client();
        $this->vkSender = new VkApiService();
        $this->postSerializer = new PostSerializer();
    }

    public function results(RequestInterface $request,
                            ResponseInterface $response) : ResponseInterface
    {
        try {
            if (isset($_COOKIE['token'])) {
                $token = $_COOKIE['token'];
            } else {
                throw new \Exception("Кука сдохла");
            }

            $links = explode("\n", $request->getParsedBody()['links']);
            $postsNumber = ($request->getParsedBody()['posts_number']);

            if (empty($links[0])) {
                throw new \Exception('Ты забыла записать ссылки');
            }

            if ($postsNumber == 'миллион') {
                throw new \Exception(
                    "Ты сломала сервер" .
                            "<br>" .
                            "<img src='img/9150e4e5ly1fh3mcehmamg2088088qlf.gif'>"
                );
            }

            $posts = [];

            foreach ($links as $link) {
                preg_match('#vk\.com/(.+)#', $link, $match);

                if (empty($match[1])) {
                    throw new \Exception("Косячная ссылка: $link");
                }

                $groupId = trim($match[1]); //убираем символ переноса строки, обусловлено вводом пользователя в форму

                $this->vkSender->setApiToken($token);
                $posts[] = $this->vkSender->getPosts($groupId, $postsNumber);
            }

            $serializePosts = $this->postSerializer->serialize($posts);

            return $this->twig->render($response, 'home/results.twig',['groupsData' => $serializePosts]);
        } catch (\Exception $e) {
            return $this->twig->render($response, 'home/error.twig', ['errorMessage' => $e->getMessage()]);
        }
    }

    public function home(RequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        $gotToken = $request->hasHeader('Referer');

        return $this->twig->render(
            $response, 'home/posts.twig', [
                'gotToken' => $gotToken
            ],
        );
    }

    public function token(RequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        try {
            if (!isset($_GET['code'])) {
                throw new \Exception('Не получилось добыть токен');
            }

            $code = $_GET['code'];

            $response = $this->client->request('GET',
                "https://oauth.vk.com/access_token?client_id=7751109&client_secret=POPldpjU2CoJj8MoDk2P&redirect_uri=http://webudm.beget.tech/token&code=$code"
            );

            $responseDataArray = json_decode($response->getBody()->getContents(), true);
            $token = $responseDataArray['access_token'];

            setcookie('token', $token, time() + 86400);

            return $response
                ->withHeader('Location', '/')
                ->withStatus(302);
        } catch (\Exception $e) {
            return $this->twig->render($response, 'home/error.twig', ['errorMessage' => $e->getMessage()]);
        }
    }
}