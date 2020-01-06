<?php


namespace App\Services;


use App\Subscriber;
use ATehnix\VkClient\Client;
use ATehnix\VkClient\Exceptions\VkException;
use Exception;

class BotService
{
    private $client;

    /**
     * BotService constructor.
     */
    public function __construct()
    {
        $this->client = new Client('5.103');
        $this->client->setDefaultToken(config('services.vk.group_token'));
    }

    /**
     * Message handler. Argument is a private message object from Callback API
     * (Don't mismatch PM object with Callback API object).
     *
     * @param $data
     * @throws VkException
     * @throws Exception
     */
    public function processMessage($data)
    {
        $text = $data['text'];
        $subscriber = Subscriber::firstOrCreate([
            'id' => $data['from_id']
        ]);
        $subscriber->messages()->createMany([
            [
                'text' => $text,
                'from' => 1
            ],
            [
                'text' => $text,
                'from' => 0
            ]
        ]);
        $this->client->request('messages.send', [
            'peer_id' => $data['from_id'],
            'message' => $text,
            'random_id' => random_int(PHP_INT_MIN, PHP_INT_MAX)
        ]);
    }
}
