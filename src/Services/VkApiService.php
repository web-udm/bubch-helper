<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class VkApiService
{
    /**
     * @var string
     */
    private string $apiToken = '9d42613238ef5c6094324a1dfe813ce0089ec8dc9e53c19f8e9d861b0c3b9f598b85df0007a5f4176007c';

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
     * @param string $groupId
     * @param int $numberOfPosts
     * @return array
     * @throws GuzzleException
     */
    public function getPosts(string $groupId, int $numberOfPosts): array
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

        $resultArr = json_decode($result, true);
        $resultArr['groupId'] = $groupId;

        return $resultArr;
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