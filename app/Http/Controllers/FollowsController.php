<?php

namespace App\Http\Controllers;

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
        
        $userToFolllowId = User::findOrFail($user_id)->id;
        
        $currentUser = Auth::user();

        if($currentUser->id === $userToFolllowId)
            return response()->json("That's not possibe", Response::HTTP_UNPROCESSABLE_ENTITY);

        if ($currentUser->followsUser($userToFolllowId)) {
            /* Has followed, unfollow */
            $currentUser->following()->detach($userToFolllowId);

            $response = 0;

        } else {
            /* Hasn't liked the post, like it */
            $currentUser->following()->attach($userToFolllowId);

            $response = 1;
        }

        return response()->json($response);

    }

}
