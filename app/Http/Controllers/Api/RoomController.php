<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    //
    public function addNewRoom(Request $request)
    {
        $roles = [
            'name' => 'required|min:1|max:200'
        ];

        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $room = new Room();
            $room->user_id = auth()->user()->id;
            $room->name = $request->name;

            if ($room->save()) {
                return ControllerHelper::generateResponse(true, 'Success', 200);
            } else {
                return ControllerHelper::generateResponse(false, 'Room Failed to save', 400);
            }
        } else {
            return ControllerHelper::generateResponse(false, $validator->getMessageBag()->first(), 400);
        }
    }

    public function getAllRooms()
    {
        $rooms = Room::paginate(20);
        return ControllerHelper::generateResponsedata(true, 'Success', $rooms);
    }

    public function getMyRoom()
    {
        $user_id = auth()->user()->id;
        $rooms = Room::where('user_id', $user_id)->get();
        return ControllerHelper::generateResponsedata(true, 'Success', $rooms);
    }

    public function deleteRoom($id)
    {
        $room = Room::find($id);
        if ($room) {
            $user_id = auth()->user()->id;
            $isDelted = Room::where('id', $id)->where('user_id', $user_id)->delete();
            if ($isDelted) {
                return ControllerHelper::generateResponse(true, 'Deleted Successfully', 200);
            } else {
                return ControllerHelper::generateResponse(false, 'This Room is not for you', 400);
            }
        } else {
            return ControllerHelper::generateResponse(false, 'Not Found', 404);
        }
    }
}
