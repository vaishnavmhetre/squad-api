<?php

namespace App\Http\Controllers;

use Auth;
use App\Comment;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth')->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($post_id)
    {
        $post = Post::findOrFail($post_id);

        $comments = $post->comments()->latest()->get();

        return response()->json($comments);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Post $post
     * @return void
     */
    public function store(Request $request, $post_id)
    {
        $this->validate($request, [
            'description' => 'required'
        ]);
        $post = Post::findOrFail($post_id);
        $comment = Comment::create([
            'description' => $request->get('description'),
	    'post_id' => $post_id,
            'user_id' => Auth::id()
        ]);

        return response()->json($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
