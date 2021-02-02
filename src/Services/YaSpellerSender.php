<?php

namespace App\Services;

use GuzzleHttp\Client;
use Hidehalo\Emoji;

class YaSpellerSender
{
    private Client $client;

    private string $url = 'https://speller.yandex.net/services/spellservice.json/checkText';

    public function __construct()
    {
        $this->client = new Client();
        $this->emojiConverter = new Emoji\Converter(new Emoji\Parser());
    }

    public function checkText(string $text)
    {
        $textWithoutPunctuation  = preg_replace('#[[:punct:]]#su','', $text);
        $textWithoutEmojies = $this->emojiConverter->encode($textWithoutPunctuation, new Emoji\Protocol\Filter());
        $cleanText = preg_replace('#\s#su', '+', $textWithoutEmojies);
        $url = $this->url . "?text=" .$cleanText;

        $response = $this->client->get($url);
        $result = json_decode($response->getBody()->getContents(), true);

        if (!empty($result)) {
            foreach ($result as $item) {
                $text = str_replace($item['word'], "<span class='wrong-word'>{$item['word']}</span>", $text);
            }
        }

        return $text;
    }
}