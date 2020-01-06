<?php

namespace App\Http\Controllers;

use App\Services\BotService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BotController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  BotService  $botService
     * @return Response
     */
    public function __invoke(Request $request, BotService $botService)
    {
        $all = $request->json()->all();
        $type = $all['type'];
        $group_id = $all['group_id'];
        $data = $all['object'] ?? null;

        if ($group_id != config('services.vk.group_id'))
            return response('Wrong group ID. Please check that you have connected this bot to right group', Response::HTTP_PRECONDITION_FAILED);
        switch ($type) {
            case 'confirmation':
                return config('services.vk.group_confirmation');
                break;
            case 'message_new':
                $botService->processMessage($data['message']);
                break;
        }
        return response('ok');
    }
}
