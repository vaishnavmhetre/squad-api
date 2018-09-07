<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likedPosts(){
        return $this->morphedByMany(Post::class, 'likeable');
    }

    public function likedComments(){
        return $this->morphedByMany(Comment::class, 'likeable');
    }

    public function following(){
        return $this->belongsToMany(User::class, 'following', 'follower_id', 'following_id');
    }

    public function followers(){
        return $this->belongsToMany(User::class, 'following', 'following_id', 'follower_id');
    }

    public function followsUser($user_id)
    {

        if ($this->following()->where('following_id', $user_id)->first() == null)
            return false;

        return true;

    }
}
