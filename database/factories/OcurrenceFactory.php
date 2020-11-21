<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ocurrence;
use Faker\Generator as Faker;

$factory->define(Ocurrence::class, function (Faker $faker) {
    $users = App\Models\User::pluck('id')->toArray();
    $types = App\Models\OcurrenceType::pluck('id')->toArray();
    return [        
        'user_id' => $faker->randomElement($users),
        'type_id' => $faker->randomElement($types),
        'violence_type' => $faker->word,
        'what_to_do' => $faker->paragraph,
    ];
});
