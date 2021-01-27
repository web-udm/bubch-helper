<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class VkApiService
{
    /**
     * @var string
     */
    private string $apiToken = '04dec25b3d2c5fdf340597befd41d10cbcb0f42245a5f2d2a302cd67197393476cf438234753569f383bb';

    /**
     * @var string
     */
    private string $apiVersion = '5.21';

    /**
     * @var string
     */
    private string $apiUrl = 'https://api.vk.com/method/';

    /**
     * Получить массив с информацией о последних постах группы
     *
     * @param int $numberOfPosts
     * @param string $groupId
     * @return string
     * @throws GuzzleException
     */
    public function getPosts(int $numberOfPosts, string $groupId): string
    {
        if (preg_match('#id(\d+)#', $groupId, $match)) {
            $result = $this->execute('wall.get', [
                'owner_id' => $match[1],
                'count' => $numberOfPosts
            ]);
        } else {
            $result = $this->execute('wall.get', [
                'domain' => $groupId,
                'count' => $numberOfPosts
            ]);
        }

        return $result;
    }

    /**
     * Выполнить запрос к API
     *
     * @param string $methodName
     * @param array $params
     * @return string
     * @throws GuzzleException
     */
    private function execute(string $methodName, array $params = []): string
    {
        $url = "{$this->apiUrl}$methodName?access_token={$this->apiToken}&v=$this->apiVersion";

        if (!empty($params)) {
            foreach ($params as $paramName => $paramValue) {
                $url .= "&$paramName=$paramValue";
            }
        }

        $response = (new Client())->request('GET', $url);

        return $response->getBody()->getContents();
    }
}