<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{

    public function __construct()
    {
//        $this->middleware(['auth']);
    }

    public function togglePostLike(Request $request, $post_id)
    {

        $post = Post::findOrFail($post_id);

        if ($post->likedByUser()) {
            /* Has liked the post, unlike it */
            $post->likers()->detach(Auth::id());

        } else {
            /* Hasn't liked the post, like it */
            $post->likers()->attach(Auth::id());
        }

        return response()->json('');
    }

    public function toggleCommentLike(Request $request, $comment_id)
    {

        $comment = Comment::findOrFail($comment_id);

        if ($comment->likedByUser()) {
            /* Has liked the post, dislike it */
            $comment->likers()->detach(Auth::id());
        } else {
            /* Hasn't liked the post, like it */
            $comment->likers()->attach(Auth::id());

        }

        return redirect()->back();
    }

    public function getPostLikes($post_id)
    {

        $post = Post::findOrFail($post_id);

        return response()->json($post->likers);
    }

    public function getCommentLikes($comment_id)
    {

        $comment = Comment::findOrFail($comment_id);

        return response()->json($comment->likers);
    }
}
