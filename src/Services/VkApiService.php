<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class VkApiService
{
    /**
     * @var string
     */
    private string $apiToken;

    /**
     * @var string
     */
    private string $apiVersion = '5.21';

    /**
     * @var string
     */
    private string $apiUrl = 'https://api.vk.com/method/';

    /**
     * @param string $token
     */
    public function setApiToken(string $token)
    {
        $this->apiToken = $token;
    }

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
        if (preg_match('#(?:id|public)(\d+)#', $groupId, $match)) {
            $result = $this->execute('wall.get', [
                'owner_id' => '-' . $match[1], //знак '-' перед id - это особенно работы api.vk
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