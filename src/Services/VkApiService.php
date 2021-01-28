<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class VkApiService
{
    /**
     * @var string
     */
    private string $apiToken = 'c7da8f7910f77e72c5df3349a2589f3c7f61f56befb563c30c9300f791ab910be61411f1e89bbce75ef83';

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