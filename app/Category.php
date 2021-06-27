<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = ['name', 'status'];

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id', 'id');
    }
}
