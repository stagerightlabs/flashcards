<?php

use App\Card;
use App\User;
use App\Domain;
use Faker\Generator as Faker;

$factory->define(Card::class, function (Faker $faker) {
    return [
        'title' => $faker->word(),
        'body' => $faker->paragraph(),
        'source' => $faker->url(),
        'created_by' => function () {
            return factory(User::class)->create()->id;
        },
        'domain_id' => function () {
            return factory(Domain::class)->create()->id;
        },
    ];
});

$factory->afterCreating(Card::class, function ($card) {
    // activity()
    //     ->performedOn($card)
    //     ->withProperties([
    //         'title' => $card->title,
    //         'body' => $card->body,
    //         'source' => $card->source,
    //     ])
    //     ->log('created');
});
