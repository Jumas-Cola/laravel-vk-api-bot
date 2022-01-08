<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use VK\Client\VKApiClient;

class NewMessageController extends Controller
{
    public function __construct()
    {
        $this->access_token = env("VK_SECRET_KEY");
        $this->vk = new VKApiClient();
        $this->buttons = json_encode([
            "one_time"=>false,
            "buttons"=>[
                [
                    [
                        "action"=>[
                            "type"=>"text",
                            "payload"=>"{\"button\":\"лоли\"}",
                            "label"=>"Лоли"
                        ],
                        "color"=>"positive"
                    ],

                    [
                        "action"=>[
                            "type"=>"text",
                            "payload"=>"{\"button\":\"гиф\"}",
                            "label"=>"GIF"
                        ],
                        "color"=>"negative"
                    ]
                ]
            ]
        ]);
    }

    public function handle(Request $request)
    {
        $object = $request->object;
        $message = $object['message'];
        $text = mb_strtolower($message['text']);

        /* Обработка комнд */
        $handler = match (true) {
            str_contains($text, 'лоли') || str_contains($text, 'loli') => 'loli', 
            str_contains($text, 'гиф') || str_contains($text, 'gif') => 'gif', 
            default => 'hello',
        };

        return $this->$handler($request);
    }

    /* 
    * Приветственное сообщение 
    */
    public function hello(Request $request)
    {
        $object = $request->object;
        $message = $object['message'];
        $id = $message['from_id'];
        $peer_id = $message['peer_id'];


        /* Формирование ответа */
        $response = [
            'peer_id' => $peer_id,
            'message' => 'Привет!',
            'random_id' => rand(),
        ];


        /*
        * Запись пользователя в базу
        * и обновление его состояния 
        */
        $user = User::find($id);
        if ( empty($user) ) {
            $user = User::create(['id' => $id]);
            $response['keyboard'] = $this->buttons;
        }
        $user->state = '"hello"';
        $user->save();


        /* Отправка ответного сообщения */
        $this->vk->messages()->send($this->access_token, $response);

        return 'ok';
    }

    /* 
    * Отправка картинки 
    */
    public function loli(Request $request)
    {
        $object = $request->object;
        $message = $object['message'];
        $id = $message['from_id'];
        $peer_id = $message['peer_id'];


        $response = [
            'peer_id' => $peer_id,
            'message' => 'Держи!',
            'attachment' => get_loli_attachment(),
            'random_id' => rand(),
        ];


        $user = User::find($id);
        if ( empty($user) ) {
            $user = User::create(['id' => $id]);
            $response['keyboard'] = $this->buttons;
        }
        $user->state = '"loli"';
        $user->save();


        $this->vk->messages()->send($this->access_token, $response);

        return 'ok';
    }

    /* 
    * Отправка GIF 
    */
    public function gif(Request $request)
    {
        $object = $request->object;
        $message = $object['message'];
        $id = $message['from_id'];
        $peer_id = $message['peer_id'];


        $response = [
            'peer_id' => $peer_id,
            'message' => 'Держи!',
            'attachment' => get_gif_attachment(),
            'random_id' => rand(),
        ];


        $user = User::find($id);
        if ( empty($user) ) {
            $user = User::create(['id' => $id]);
            $response['keyboard'] = $this->buttons;
        }
        $user->state = '"gif"';
        $user->save();


        $this->vk->messages()->send($this->access_token, $response);

        return 'ok';
    }
}
