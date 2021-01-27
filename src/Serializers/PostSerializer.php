<?php

namespace App\Serializers;

class PostSerializer
{
    public function serialize($groupsData)
    {
        $serializePosts = [];

        foreach ($groupsData as $group) {
            foreach ($group['response']['items'] as $post) {
                $groupId = $post['from_id'];
                $postId = $post['id'];
                $serializePosts[$groupId][$postId]['link'] = "https://vk.com/klubtaper_izh?w=wall{$groupId}_{$postId}";
                $serializePosts[$groupId][$postId]['date'] = $post['date'];
                $serializePosts[$groupId][$postId]['text'] = $post['text'];
                foreach ($post['attachments'] as $attachment) {
                    if ($attachment['type'] = 'photo') {
                        $serializePosts[$groupId][$postId]['images'][] = $attachment['photo']['photo_1280'];
                    }
                }
            }
        }

        return $serializePosts;
    }
}