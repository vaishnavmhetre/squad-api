<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $followings = $user->following;
        if (count($followings) < 3)
            return response()->json('Less than 3 following', Response::HTTP_EXPECTATION_FAILED);
        $posts = new Collection;
        $user->load('following.posts');
        foreach ($user->following as $following)
            $posts = $posts->merge($following->posts);

        $posts = $posts->unique();

        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required'
        ]);

        $post = Post::create([
            'description' => $request->get('description'),
            'user_id' => Auth::id()
        ]);

        return response()->json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function show($post_id)
    {
        $post = Post::findOrFail($post_id);
        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }


    public function getSelfPosts(){

        $user = Auth::user();

        $posts = $user->posts;

        return response()->json($posts);

    }

    public function getPosts($user_id){

        $user = User::findOrFail($user_id);

        $posts = $user->posts;

        return response()->json($posts);

    }
}
