<?php

use App\Card;
use App\User;
use App\Domain;
use Faker\Generator as Faker;

$factory->define(Card::class, function (Faker $faker) {
    return [
        'title' => $faker->words(mt_rand(1, 5), true),
        'body' => $faker->paragraph(),
        'source' => $faker->optional()->url(),
        'created_by' => function () {
            return factory(User::class)->create()->id;
        },
        'domain_id' => function () {
            return factory(Domain::class)->create()->id;
        },
    ];
});
