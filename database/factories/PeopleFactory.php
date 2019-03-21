<?php

use Faker\Generator as Faker;

$factory->define(App\Building::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'address' => $faker->address
    ];
});

$factory->define(App\Group::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'priority' => 3
    ];
});

$factory->define(App\People::class, function (Faker $faker) {
    return [
        'fio' => $faker->name,
        'position' => $faker->name,
        'building_id' => $faker->numberBetween(1, 10),
        'group_id' => $faker->numberBetween(1, 10)
    ];
});



