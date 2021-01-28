<?php

namespace App\Helpers;

class TextHelper
{
    public function calculateHashtags(string $text)
    {
        preg_match_all('/#w+/u', $text, $matches);
        var_dump($matches);
    }
}