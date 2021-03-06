<?php

use App\Domain;
use App\Tenant;
use Faker\Generator as Faker;

$factory->define(Domain::class, function (Faker $faker) {
    return [
        'name' => 'Plant Breeding',
        'tenant_id' => function () {
            return factory(Tenant::class)->create()->id;
        },
    ];
});

$factory->afterCreating(Domain::class, function ($domain) {
    activity()
        ->performedOn($domain)
        ->withProperties([
            'name' => $domain->name,
        ])
        ->log('created');
});
