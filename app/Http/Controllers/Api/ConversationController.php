<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use Illuminate\Http\Request;
use Pusher\Pusher;

class ConversationController extends Controller
{
    //
    public function storeMessage(Request $request)
    {
        $conversation = new Conversation();
        $conversation->body = $request->body;
        $conversation->user_id = auth()->user()->id;
        $conversation->room_id = $request->room_id;
        if ($conversation->save()) {
            $data = Conversation::where('id', $conversation->id)->with('user', 'room')->get()[0];
            $this->trigger_pusher('room.' . $data->room->id, 'new_message', $data);
            return ControllerHelper::generateResponsedata(true, 'Success', $data);
        } else {
            return ControllerHelper::generateResponse(false, 'Failed to save data', 400);
        }
    }

    protected function trigger_pusher($roomChannel, $event, $data)
    {
        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'));
        $pusher->trigger($roomChannel, $event, [$data]);
    }
}
