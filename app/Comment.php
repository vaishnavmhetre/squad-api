<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likers()
    {
        return $this->morphToMany(User::class, 'likeable');
    }

    public function likedByUser($user_id = null)
    {
        $user_id = $user_id != null ? $user_id : Auth::id();

        if ($this->likers()->where('user_id', $user_id)->first() == null)
            return false;

        return true;

    }
}
