<?php

use VK\Client\VKApiClient;

if ( !function_exists('get_loli_attachment') ) {
    function get_loli_attachment()
    {
        $vk = new VKApiClient();
        $group_id = -130705616;

        $count = $vk->wall()->get(env('VK_SERVICE_KEY'), [
            'owner_id' => $group_id,
            'filter' => 'owner'
        ])['count'];

        for ($i = 0; $i < 10; $i++) {
            $item = $vk->wall()->get(env('VK_SERVICE_KEY'), [
                'owner_id' => $group_id,
                'count' => 1,
                'offset' => rand(0, $count),
                'filter' => 'owner'
            ])['items'][0];

            if ( $item['marked_as_ads'] ) continue;

            $attachments = $item['attachments'];

            if ( empty($attachments) ) continue;

            $res_str = '';

            foreach ( $attachments as $a ) {
                if ( $a['type'] == 'photo' ) {
                    $res_str .= "photo{$a['photo']['owner_id']}_{$a['photo']['id']},";
                }
            }

            if ( empty($res_str) ) continue;

            return $res_str;
        }
    }
}

if ( !function_exists('get_gif_attachment') ) {
    function get_gif_attachment()
    {
        $vk = new VKApiClient();
        $group_id = -47151724;

        $count = $vk->wall()->get(env('VK_SERVICE_KEY'), [
            'owner_id' => $group_id,
            'filter' => 'owner'
        ])['count'];

        for ($i = 0; $i < 10; $i++) {
            $item = $vk->wall()->get(env('VK_SERVICE_KEY'), [
                'owner_id' => $group_id,
                'count' => 1,
                'offset' => rand(0, $count),
                'filter' => 'owner'
            ])['items'][0];

            if ( $item['marked_as_ads'] ) continue;

            if ( !array_key_exists('attachments', $item) ) continue;

            $attachments = $item['attachments'];

            if ( empty($attachments) ) continue;

            $res_str = '';

            foreach ( $attachments as $a ) {
                if ( $a['type'] == 'doc' and $a['doc']['ext'] == 'gif' ) {
                    $res_str .= "doc{$a['doc']['owner_id']}_{$a['doc']['id']}_{$a['doc']['access_key']},";
                }
            }

            if ( empty($res_str) ) continue;

            return $res_str;
        }
    }
}
