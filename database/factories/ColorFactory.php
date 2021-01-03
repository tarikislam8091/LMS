<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Color;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'color_name' => $faker->color_name,
        'color_status' => $faker->color_status
    ];
});
