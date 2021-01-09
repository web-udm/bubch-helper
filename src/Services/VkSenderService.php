<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class VKRequestService
{
    /**
     * @var string
     */
    private string $apiToken = '84ef5ca10df961503901d08aa2eb957430dfabf1dc22d9ac6d410dad245a4428fa66be7b8c51f458f25e7';

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
     * @param array $groupsIDs
     * @return array
     * @throws GuzzleException
     */
    public function getPosts(int $numberOfPosts, array $groupsIDs) : array
    {
        while ($groupsIDs) {
            $groupId = array_shift($groupsIDs);
            $result = $this->execute('wall.get', ['owner_id'=> '44690654']);
        }

        while ($numberOfPosts) {

        }
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
        $url = "{$this->apiUrl}$methodName?access_token={$this->apiToken}&$this->apiVersion";
        if (!empty($params)) {
            foreach ($params as $paramName => $paramValue) {
                $url .= "&$paramName=$paramValue";
            }
        }

        $response = (new Client())->request('GET', $url);

        return $response->getBody()->getContents();
    }
}