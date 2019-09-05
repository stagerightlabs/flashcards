<?php

use App\User;
use App\Tenant;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'remember_token' => Str::random(10),
        'tenant_id' => function () {
            return factory(Tenant::class)->create()->id;
        },
        'current_domain_id' => null,
    ];
});

$factory->state(User::class, 'admin', [
    'is_admin' => true,
]);
