<?php


namespace App\Services;


use ATehnix\VkClient\Client;

class BotService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client('5.103');
        $this->client->setDefaultToken(config('services.vk.group_token'));
    }

    public function processMessage($data)
    {
        $text = $data['text'];
        $subscriber = $data['from_id'];
        $this->client->request('messages.send', [
            'peer_id' => $subscriber,
            'message' => $text,
            'random_id' => random_int(PHP_INT_MIN, PHP_INT_MAX)
        ]);
    }
}
