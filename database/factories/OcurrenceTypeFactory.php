<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\OcurrenceType;
use Faker\Generator as Faker;

$factory->define(OcurrenceType::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'status' => $faker->randomElements([
            'leve',
            'media',
            'pesada',
            ]),            
    ];
});
