<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(CommentsTableSeeder::class);
        $this->call(LikesSeeder::class);
    }
}

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'email' => 'mhetrevaishnav0@gmail.com'
        ]);

        factory(\App\User::class, 9)->create();
    }
}

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Post::class, 5)->create();
    }
}

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = \App\Post::first() == null ? factory(\App\Post::class, 10)->create() : \App\Post::all();

        foreach ($posts as $post) {
            factory(\App\Comment::class, 5)->create(['post_id' => $post->id]);
        }
    }
}

class LikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /* Retrieve existing Users or create new */

        $user_ids = \App\User::first() == null
                        ? factory(\App\User::class, 10)->create()
                        : \App\User::all()->random(10)->pluck('id');

        /* Posts Liker */

        $posts = \App\Post::first() == null
                        ? factory(\App\Post::class, 10)->create()
                        : \App\Post::all();

        foreach ($posts as $post)
            foreach ($user_ids as $user_id)
                if (!$post->likedByUser($user_id))
                    $post->likers()->attach($user_id);


        /* Comments liker */

        $comments = \App\Comment::first() == null
                        ? factory(\App\Comment::class, 10)->create()
                        : \App\Comment::all();

        foreach ($comments as $comment)
            foreach ($user_ids as $user_id)
                if (!$comment->likedByUser($user_id))
                    $comment->likers()->attach($user_id);
    }
}

