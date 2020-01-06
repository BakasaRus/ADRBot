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

Если Вас устраивают приведённые выше условия прохождения опроса, введите \"Далее'\", и мы перейдём непосредственно к опросу. В противном случае нажмите кнопку \"Отказаться\", и мы Вас больше не потревожим :)"
        ],
        'bye' => [
            "Это был последний вопрос. До свидания и спасибо Вам за участие в опросе!"
        ],
        'rejected' => [
            'До свидания! Если передумаете, напишите любое сообщение ;-)'
        ],
        'change_mind' => [
            "Всё же решили пройти опрос? Условия остались теми же, что мы присылали в самом начале, нужно только подтвержить согласие с ними.

Нажмите \"Далее\", чтобы перейти к опросу, или \"Отказаться\", чтобы не проходить его."
        ],
        'already_passed' => [
            'Вы уже прошли участие в опросе, спасибо! К сожалению, мы принимаем только одну анкету с одного аккаунта ВКонтакте.'
        ],
        'confirm_sex' => [
            'Мы получили из Вашего аккаунта ВКонтакте информацию, что Ваш пол -- %s. Это так?'
        ],
        'ask_sex' => [
            'Уточните, пожалуйста, Ваш пол. Отправьте одно слово, "мужской" или "женский".'
        ],
        'confirm_age' => [
            'Из Вашего же аккаунта ВКонтакте мы узнали, что Вам %s. Всё верно?'
        ],
        'ask_age' => [
            'Уточните, пожалуйста, Ваш возраст. Отправьте полное число лет, например, "21".'
        ],
        'move_to_questions' => [
            'Окей, с возрастом и полом разобрались. Передём к интересной части опроса.'
        ],
        'ask_diagnosis' => [
            'Есть ли у Вас какой-либо диагноз, который Вам поставил врач? Если есть, расскажите о нём, если нет, то просто напишите "Нет".'
        ],
        'ask_adr_drug' => [
            'Из-за какого препарата, на Ваш взгляд, возникли побочные эффекты?'
        ],
        'ask_other_drugs' => [
            'А какие-нибудь другие препараты Вы принимали вместе с этим?'
        ],
        'ask_adr' => [
            'Теперь о главном: расскажите непосредственно про побочные эффекты, которые у Вас проявились. Это можно сделать в свободной форме, чем подробнее, тем лучше.'
        ],
        'ask_risks' => [
            'Делали ли Вы что-либо, что не рекомендуется при принятии препарата (например, принимали алкоголь)?'
        ],
        'dummy' => [
            'Мы Вас, к сожалению, не совсем поняли. Попробуйте написать то, что хотели, чуть иначе ;-)'
        ]
    ];

    private $keyboards = [
        'empty' => '{ "buttons": [], "one_time": true }',
        'agree' => '{
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
			}',
        'sex' => '{
			  "one_time": true,
			  "buttons": [
			    [
			      {
			        "action": {
			          "type": "text",
			          "label": "Мужской"
			        },
			        "color": "secondary"
			      },
			      {
			        "action": {
			          "type": "text",
			          "label": "Женский"
			        },
			        "color": "secondary"
			      }
			    ]
			  ]
			}',
        'yes_no' => '{
			  "one_time": true,
			  "buttons": [
			    [
			      {
			        "action": {
			          "type": "text",
			          "label": "Да"
			        },
			        "color": "positive"
			      },
			      {
			        "action": {
			          "type": "text",
			          "label": "Нет"
			        },
			        "color": "negative"
			      }
			    ]
			  ]
			}',
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

    private function randElement(array $a)
    {
        $index = array_rand($a);
        return $a[$index];
    }

    private function genMessage($category, $args = [])
    {
        return vsprintf($this->randElement($this->texts[$category]), $args);
    }

    /**
     * @param string $text
     * @param Subscriber $subscriber
     * @return string
     */
    private function getMessageAction($text, $subscriber)
    {
        $state = $subscriber->state;

        if (is_null($state)) {
            return 'hello';
        }

        $text = mb_strtolower($text);

        switch ($state) {
            case 'hello':
            case 'change_mind':
                if ($text == 'далее' || $text == 'да') {
                    $action = $subscriber->sex == 0 ? 'ask_sex' : 'confirm_sex';
                } else {
                    $action = 'rejected';
                }
                break;
            case 'rejected':
                $action = 'change_mind';
                break;
            case 'confirm_sex':
                if ($text == 'да') {
                    $action = $subscriber->age == 0 ? 'ask_age' : 'confirm_age';
                } else {
                    $action = 'ask_sex';
                }
                break;
            case 'ask_sex':
                if ($text == 'мужской' || $text == 'муж' || $text == 'мужчина' ||
                    $text == 'женский' || $text == 'жен' || $text == 'женщина') {
                    $action = $subscriber->age == 0 ? 'ask_age' : 'confirm_age';
                } else {
                    $action = 'ask_sex';
                }
                break;
            case 'confirm_age':
                $action = $text == 'да' ? 'diagnosis' : 'ask_age';
                break;
            case 'ask_age':
                $action = is_numeric($text) ? 'diagnosis' : 'ask_age';
                break;
            case 'diagnosis':
                $action = 'adr_drug';
                break;
            case 'adr_drug':
                $action = 'adr';
                break;
            case 'adr':
                $action = 'other_drugs';
                break;
            case 'other_drugs':
                $action = 'risks';
                break;
            case 'risks':
                $action = 'bye';
                break;
            case 'passed':
                $action = 'already_passed';
                break;
            default:
                $action = 'dummy';
        }

        return $action;
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
        if (is_null($subscriber->name)) {
            $info = $this->client->request('users.get', [
                'user_ids' => $data['from_id'],
                'fields' => 'sex,bdate'
            ])['response'][0];
            $subscriber = $subscriber->setInfoFromVk($info);
        }
        $state = $subscriber->state;
        $new_state = '';
        $keyboard = $this->keyboards['empty'];

        $route = $this->getMessageAction($text, $subscriber);
        switch ($route) {
            case 'hello':
                $new_state = 'hello';
                $reply = $this->genMessage('hello');
                $keyboard = $this->keyboards['agree'];
                break;
            case 'confirm_sex':
                $new_state = 'confirm_sex';
                $reply = $this->genMessage('confirm_sex', [$subscriber->readable_sex]);
                $keyboard = $this->keyboards['yes_no'];
                break;
            case 'ask_sex':
                $new_state = 'ask_sex';
                $reply = $this->genMessage('ask_sex');
                $keyboard = $this->keyboards['sex'];
                break;
            case 'confirm_age':
                if ($state == 'ask_sex') {
                    $subscriber->readable_sex = mb_strtolower($text);
                }
                $new_state = 'confirm_age';
                $reply = $this->genMessage('confirm_age', [$subscriber->age]);
                $keyboard = $this->keyboards['yes_no'];
                break;
            case 'ask_age':
                if ($state == 'ask_sex') {
                    $subscriber->readable_sex = mb_strtolower($text);
                }
                $new_state = 'ask_age';
                $reply = $this->genMessage('ask_age');
                break;
            case 'diagnosis':
                if ($state == 'ask_age') {
                    $subscriber->age = (int)$text;
                }
                $this->client->request('messages.send', [
                    'peer_id' => $data['from_id'],
                    'message' => $this->genMessage('move_to_questions'),
                    'random_id' => random_int(PHP_INT_MIN, PHP_INT_MAX)
                ]);
                $new_state = 'diagnosis';
                $reply = $this->genMessage('ask_diagnosis');
                break;
            case 'adr_drug':
                $subscriber->diagnosis = $text;
                $new_state = 'adr_drug';
                $reply = $this->genMessage('ask_adr_drug');
                break;
            case 'adr':
                $subscriber->adr_drug = $text;
                $new_state = 'adr';
                $reply = $this->genMessage('ask_adr');
                break;
            case 'other_drugs':
                $subscriber->adr = $text;
                $new_state = 'other_drugs';
                $reply = $this->genMessage('ask_other_drugs');
                break;
            case 'risks':
                $subscriber->other_drugs = $text;
                $new_state = 'risks';
                $reply = $this->genMessage('ask_risks');
                break;
            case 'bye':
                $subscriber->risks = $text;
                $new_state = 'passed';
                $reply = $this->genMessage('bye');
                break;
            case 'already_passed':
                $reply = $this->genMessage('already_passed');
                break;
            case 'rejected':
                $new_state = 'rejected';
                $reply = $this->genMessage('rejected');
                break;
            case 'change_mind':
                $new_state = 'change_mind';
                $reply = $this->genMessage('change_mind');
                $keyboard = $this->keyboards['agree'];
                break;
            case 'dummy':
            default:
                $reply = 'Я чёт не совсем понимаю Вас :-(';
                break;
        }
        $subscriber->state = $new_state;
        $subscriber->save();
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
