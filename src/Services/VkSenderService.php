<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class VkSenderService
{
    /**
     * @var string
     */
    private string $apiToken = 'cbf1b1e2d11b686cdb62b09e55d2e6c55b1c6287cb948c62a3f09c97fe9f6f8d33c48a6d38bfc3f443b15';

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
    public function getPosts(int $numberOfPosts, string $groupId) : string
    {
            $result = $this->execute('wall.get', [
                'owner_id'=> $groupId,
                'count' => $numberOfPosts
            ]);

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
    private function execute(string $methodName, array $params = []) : string
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