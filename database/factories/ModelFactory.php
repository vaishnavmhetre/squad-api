<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
    ];
});


$factory->define(App\Post::class, function (Faker\Generator $faker) {
    return [
        'description' => $faker->paragraph,
        'user_id' =>
            \App\User::first() == null
                ? factory(\App\User::class)->create()->id
                : \App\User::all()->random(1)[0]->id
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    return [
        'description' => $faker->paragraph,
        'post_id' => \App\Post::first() == null ? factory(\App\Post::class)->create()->id : \App\Post::all()->random(1)[0]->id,
        'user_id' => \App\User::first() == null ? factory(\App\User::class)->create()->id : \App\User::all()->random(1)[0]->id
    ];
});
