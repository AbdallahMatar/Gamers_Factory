<?php

namespace App\Http\Controllers\Api;

use App\Events\listenMessage;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    //
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'to_user_id' => 'required|exists:users,id'
        ]);

        $message = new Message();
        $message->message = $request->message;
        $message->to_user_id = $request->to_user_id;
        $message->from_user_id = auth()->user()->id;

        $isSaved = $message->save();
        if ($isSaved) {
            broadcast(new listenMessage($message));
            return response()->json([
                'status' => true,
                'message' => 'Message send successfully',
                'data' => $message
            ]);
        } else {
            return ControllerHelper::generateResponse(false, 'Message Faile send', 400);
        }
    }

    public function getMessagesAuthToUser($id)
    {
        $user_id = auth()->user()->id;
        $data = Message::where(['to_user_id' => $id, 'from_user_id' => $user_id])
            ->orWhere('from_user_id', $id)->where('to_user_id', $user_id)->take(20)->get();

        // return ControllerHelper::generateResponsedata(true, 'Success', $data);
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'messageData' => $data
        ]);
    }

    public function readMessage($id)
    {
        $message = Message::find($id);
        if ($message) {
            $message->is_read = 1;
            $message->update();
            return ControllerHelper::generateResponse(true, 'Success', 200);
        } else {
            return ControllerHelper::generateResponse(false, 'Not Found', 404);
        }
    }

    public function readMessages($id)
    {
        $message = Message::where('from_user_id', $id);
        if ($message) {
            $message->update(['is_read' => 1]);
            return ControllerHelper::generateResponse(true, 'Success', 200);
        } else {
            return ControllerHelper::generateResponse(false, 'Not Found', 404);
        }
    }

    public function getMessagesAuth()
    {
        $authUserId = auth()->user()->id;
        $message_detail = Message::with(['fromUser', 'toUser'])
            ->select(DB::raw('count(*) as total'), 'from_user_id', 'to_user_id')
            ->where('to_user_id', $authUserId)->where('is_read', 0)
            ->groupBy('from_user_id', 'to_user_id')->get();

        foreach ($message_detail as $key => $message) {
            $ka = Message::select('message', 'is_read')
                ->where(['to_user_id' => $authUserId, 'from_user_id' => $message->from_user_id])
                ->where('is_read', 0)->get()->last();

            $message_detail[$key]->last_message = $ka->message;
            $message_detail[$key]->is_read = $ka->is_read;
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'message detail' => $message_detail
        ]);
    }
}
