<?php

namespace App\Helpers;

use Hidehalo\Emoji\Parser;

class TextHelper
{
    public function calculateHashtags(string $text)
    {
        preg_match_all('/#\w+/u', $text, $matches);

        return $matches[0];
    }

    public function calculateEmoji(string $text)
    {
        $parser = new Parser();

        return $parser->parse($text);
    }
}