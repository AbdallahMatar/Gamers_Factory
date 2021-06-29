<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_friend extends Model
{
    //
    protected $fillable = ['accepted', 'friend_id', 'user_id',];

    public function users()
    {
        return $this->belongsToMany(User::class, User_friend::class, 'friend_id', 'user_id')->withTimestamps();
    }
}
