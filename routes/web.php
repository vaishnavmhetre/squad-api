<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'users'], function () use ($router) {

    $router->group(['prefix' => '{user_id}'], function () use ($router) {

        $router->get('/', ['as' => 'users.show', 'uses' => 'UserController@show']);

    });

});

$router->group(['prefix' => 'posts'], function () use ($router) {

    $router->get('/', ['as' => 'posts.index', 'uses' => 'PostController@index']);

    $router->group(['prefix' => '{post_id}'], function () use ($router) {

        $router->get('/', ['as' => 'posts.show', 'uses' => 'PostController@show']);

        $router->get('/comments', ['as' => 'posts.comments.index', 'uses' => 'CommentController@index']);

        $router->post('/comments', ['as' => 'posts.comments.store', 'uses' => 'CommentController@store']);

        $router->group(['prefix' => 'likes'], function () use ($router) {

            $router->get('/', ['as' => 'posts.likes', 'uses' => 'LikeController@getPostLikes']);

            $router->post('/toggle', ['as' => 'posts.likes.toggle', 'uses' => 'LikeController@togglePostLike']);

        });

    });

});

$router->group(['prefix' => 'comments'], function () use ($router) {

    $router->group(['prefix' => '{comment_id}'], function () use ($router) {

        $router->group(['prefix' => 'likes'], function () use ($router) {

            $router->get('/', ['as' => 'comments.likes', 'uses' => 'LikeController@getCommentLikes']);

            $router->post('/toggle', ['as' => 'comments.likes.toggle', 'uses' => 'LikeController@toggleCommentLike']);

        });

    });

});

