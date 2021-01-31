<?php

namespace App\Serializers;

use App\Helpers\TextHelper;

class PostSerializer
{
    protected TextHelper $textHelper;

    public function __construct()
    {
        $this->textHelper = new TextHelper();
    }

    public function serialize($groupsData)
    {
        $serializePosts = [];

        foreach ($groupsData as $group) {
            $groupName = $group['groupId'];
            foreach ($group['response']['items'] as $post) {
                $groupId = $post['from_id'];
                $postId = $post['id'];

                $serializePosts[$groupName][$postId]['link'] = "https://vk.com/$groupName?w=wall{$groupId}_{$postId}";
                $serializePosts[$groupName][$postId]['date'] = $post['date'];
                $serializePosts[$groupName][$postId]['text'] = $post['text'];
                $serializePosts[$groupName][$postId]['hashtags'] = $this->textHelper->calculateHashtags($post['text']);
                $serializePosts[$groupName][$postId]['hashtags'] = $this->textHelper->calculateEmoji($post['text']);

                if (isset($post['attachments'])) {
                    foreach ($post['attachments'] as $attachment) {
                        if ($attachment['type'] == 'photo') {
                            $serializePosts[$groupName][$postId]['images'][] = $attachment['photo']['photo_604'];
                        }
                    }
                }
            }
        }

        return $serializePosts;
    }
}