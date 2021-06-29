<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use App\User_friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    //
    public function getAllFriend()
    {
        $friends = Auth::user()->friends()->where('accepted', 'friend')->get();
        if ($friends) {
            return ControllerHelper::generateResponsedata(true, 'Success', $friends);
        } else {
            return ControllerHelper::generateResponse(false, 'you dont have friend yet', 400);
        }
    }

    public function addFriend($id)
    {
        $userFriend = User_friend::where('friend_id', $id)->first();
        if ($userFriend) {
            return ControllerHelper::generateResponse(false, 'this user already friend', 400);
        } else {
            Auth::user()->friends()->attach([$id]);

            return ControllerHelper::generateResponse(true, 'Success', 200);
        }
    }

    public function pendingFriend()
    {
        $userAuth = Auth::user()->id;
        $pendingFriend = User_friend::where('friend_id', $userAuth)->where('accepted', 'pending')->get();
        return ControllerHelper::generateResponsedata(true, 'Success', $pendingFriend);
    }

    public function acceptFriend($id)
    {
        $friend = User_friend::find($id);
        if ($friend) {
            $friend->update([
                'accepted' => 'friend'
            ]);
            return ControllerHelper::generateResponse(true, 'Success', 200);
        } else {
            return ControllerHelper::generateResponse(false, 'Not Found', 404);
        }
    }

    public function removeFriend($id)
    {
        // $code = DB::table('friend_user')->where('friend_id', '=', $id)->get();
        $userFriend = User_friend::where('friend_id', $id)->first();
        if ($userFriend) {
            $isDelted = Auth::user()->friends()->detach([$id]);
            if ($isDelted) {
                return ControllerHelper::generateResponse(true, 'Deleted Successfully', 200);
            } else {
                return ControllerHelper::generateResponse(false, 'Deleted Failed', 400);
            }
        } else {
            return ControllerHelper::generateResponse(false, 'Not Found', 404);
        }
    }
}
