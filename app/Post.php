<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    protected $guarded = [];

    public function creator(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function link(){
        return route('posts.show', ['post_id' => $this->id]);
    }

    public function likers(){
        return $this->morphToMany(User::class, 'likeable');
    }

    public function likedByUser($user_id = null){
        $user_id = $user_id != null ? $user_id : Auth::id();

        if ($this->likers()->where('user_id', Auth::id())->first() == null)
            return false;

        return true;

    }

}
