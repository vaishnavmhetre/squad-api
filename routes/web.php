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

use Symfony\Component\HttpFoundation\Response;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['middleware' => 'auth:api'], function () use ($router) {

    $router->get('/auth/user', function () {
        return response()->json(Auth::user());
    });

    $router->group(['prefix' => 'users'], function () use ($router) {

        $router->group(['prefix' => 'me'], function () use ($router) {

            $router->get('/', ['as' => 'users.self.show', 'uses' => 'UserController@show']);

            $router->get('/followers', ['as' => 'users.self.followers.show', 'uses' => 'FollowsController@getSelfFollowers']);

            $router->get('/followings', ['as' => 'users.self.following.show', 'uses' => 'FollowsController@getSelfFollowing']);

            $router->post('/follow', ['as' => 'users.self.follow.toggle', 'uses' => 'FollowsController@toggleFollow']);

            $router->get('/posts', ['as' => 'users.self.posts.show', 'uses' => 'PostController@getSelfPosts']);

            $router->group(['prefix' => 'notifications'], function () use ($router) {

                $router->get('/', ['as' => 'users.self.notifications.show', 'uses' => 'NotificationsController@index']);

                $router->post('/markasread', 'NotificationsController@markAsRead');

                $router->post('/markasread/all', 'NotificationsController@markAllAsRead');


            });
        });

        $router->group(['prefix' => '{user_id}'], function () use ($router) {

            $router->get('/', ['as' => 'users.show', 'uses' => 'UserController@show']);

            $router->get('/followers', ['as' => 'users.followers.show', 'uses' => 'FollowsController@getFollowers']);

            $router->get('/followings', ['as' => 'users.following.show', 'uses' => 'FollowsController@getFollowing']);

            $router->get('/posts', ['as' => 'users.posts.show', 'uses' => 'PostController@getPosts']);

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

    $router->get('/test/notifications', function () {
        return response()->json(App\User::find(7)->notifications);
    });

    $router->get('/test/notifications/unread', function () {
        return response()->json(App\User::find(7)->unreadNotifications);
    });

    $router->post('/test/notifications/markasread/all', function (\Illuminate\Http\Request $request) {
        $unreadNotifications = App\User::findOrFail(7)->unreadNotifications;

        if (count($unreadNotifications) > 0)
            $unreadNotifications->markAsRead();
        else
            return response()->json('No notifications to mark as read', Response::HTTP_NOT_ACCEPTABLE);

        return response()->json('All notifications marked as read', Response::HTTP_OK);
    });

    $router->post('/test/notifications/markasread', function (\Illuminate\Http\Request $request) {
        $this->validate($request, [
            'notification_id' => 'required|exists:notifications,id'
        ]);

        $notification = App\User::findOrFail(7)->unreadNotifications()->findOrFail($request->notification_id);
        $notification->markAsRead();
        return response()->json($notification, Response::HTTP_OK);
    });

});

