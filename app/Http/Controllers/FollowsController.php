<?php

namespace App\Http\Controllers;

use App\Notifications\FollowNotification;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FollowsController extends Controller
{

    public function getSelfFollowing(){

        $user = Auth::user();

        $following = $user->following;

        return response()->json($following);

    }

    public function getSelfFollowers(){

        $user = Auth::user();

        $followers = $user->followers;

        return response()->json($followers);

    }

    public function getFollowing($user_id){

        $user = User::findOrFail($user_id);

        $following = $user->following;

        return response()->json($following);

    }

    public function getFollowers($user_id){

        $user = User::findOrFail($user_id);

        $followers = $user->followers;

        return response()->json($followers);

    }

    public function toggleFollow(Request $request){

        $this->validate($request, [
            'user_id' => 'required|exists:users,id'
        ]);

        $userToFollow = User::findOrFail($request->user_id);
        
        $userToFollowId = $userToFollow->id;
        
        $currentUser = Auth::user();

        if($currentUser->id === $userToFollowId)
            return response()->json("That's not possible", Response::HTTP_UNPROCESSABLE_ENTITY);

        if ($currentUser->followsUser($userToFollowId)) {
            /* Has followed, unfollow */
            $currentUser->following()->detach($userToFollowId);

            $response = 0;

        } else {
            /* Hasn't liked the post, like it */
            $currentUser->following()->attach($userToFollowId);

            $userToFollow->notify(new FollowNotification($currentUser));

            $response = 1;
        }

        return response()->json($response);

    }

}
