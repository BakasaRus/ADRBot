<?php


namespace App\Services;


use App\Subscriber;
use ATehnix\VkClient\Client;
use ATehnix\VkClient\Exceptions\VkException;
use Exception;

class BotService
{
    private $texts = [
        'hello' => [
            "Приветствуем! Мы -- небольшая группа студентов-исследователей, собирающих информацию о проявлениях различных побочных эффектов (которые ещё называют нежелательными лекарственными реакциями, сокращённо НЛР). Целью нашего исследования является анализ полученных данных с последующим предоставлением результатов.

Собственно, для пополнения нашей базы знаний нам нужна Ваша помощь. Мы предложим Вам несколько вопросов, которые займут максимум 10 минут. Мы не запрашиваем и не храним информацию, позволяющую идентифицировать Вас, однако для более детального исследования попросим Вас указать возраст и пол, если они не указаны в Вашем профиле ВКонтакте. Никакие данные не будут переданы третьим лицам, и после окончания исследования у нас останутся только общие данные по лекарствам без указания конкретных источников.

Если у Вас появятся вопросы по опросу, @id71397685 (Виктория) с радостью на них ответит. По поводу работоспособности самого опроса пишите @id73991663 (Николаю), и он постарается разобраться.

Вас устраивают приведённые выше условия прохождения опроса, введите \"Далее'\", и мы перейдём непосредственно к опросу. В противном случае нажмите кнопку \"Отказаться\", и мы Вас больше не потревожим :)"
        ],
        'bye' => [
            "До свидания!"
        ],
        'confirm_sex' => [

        ],
        'ask_sex' => [

        ],
        'confirm_age' => [

        ],
        'ask_age' => [

        ],
        'confirm_diagnosis' => [

        ],
        'ask_diagnosis' => [

        ],
        'ask_adr_drug' => [

        ],
        'ask_other_drugs' => [

        ],
        'more_other_drugs' => [

        ],
        'ask_adr' => [

        ],
        'more_adr' => [

        ],
        'ask_risks' => [

        ],
        'more_risks' => [

        ],
        'dummy' => [

        ]
    ];

    private $keyboards = [
        'empty' => '{ "buttons": [], "one_time": true }',
        'yes_no' => '{
			  "one_time": true,
			  "buttons": [
			    [
			      {
			        "action": {
			          "type": "text",
			          "label": "Далее"
			        },
			        "color": "positive"
			      },
			      {
			        "action": {
			          "type": "text",
			          "label": "Отказаться"
			        },
			        "color": "negative"
			      }
			    ]
			  ]
			}'
    ];

    private $client;

    /**
     * BotService constructor.
     */
    public function __construct()
    {
        $this->client = new Client('5.103');
        $this->client->setDefaultToken(config('services.vk.group_token'));
    }

    public function randElement(array $a)
    {
        $index = array_rand($a);
        return $a[$index];
    }

    public function genMessage($category, $args = [])
    {
        return vsprintf($this->randElement($this->texts[$category]), $args);
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
        $keyboard = $this->keyboards['empty'];
        switch ($subscriber->state) {
            case 'hello':
                $reply = $this->genMessage('hello');
                $keyboard = $this->keyboards['yes_no'];
                break;
            default:
                $reply = 'Я чёт не совсем понимаю Вас :-(';
                break;
        }
        $subscriber->messages()->createMany([
            [
                'text' => $text,
                'from' => 1
            ],
            [
                'text' => $reply,
                'from' => 0
            ]
        ]);
        $this->client->request('messages.send', [
            'peer_id' => $data['from_id'],
            'message' => $reply,
            'keyboard' => $keyboard,
            'random_id' => random_int(PHP_INT_MIN, PHP_INT_MAX)
        ]);
    }
}
