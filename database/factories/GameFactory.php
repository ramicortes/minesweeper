<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Game;
use App\User;
use Faker\Generator as Faker;

$factory->define(Game::class, function (Faker $faker) {
    $rows = $faker->numberBetween(0, 20);
    $columns = $faker->numberBetween(0, 20);
    return [
        'rows' => $rows,
        'columns' => $columns,
        'mines' => $faker->numberBetween(0, ($rows * $columns / 5)),
        'ended_at' => null,
        'user_id' => factory(User::class)->create(),
    ];
});
