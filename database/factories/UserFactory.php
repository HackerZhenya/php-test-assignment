<?php

use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {

    $gender = $faker->optional()->randomElement(['male', 'female']);
    $name = $faker->name($gender);

    return [
        'name' => $name,
        'gender' => $gender,
        'date_of_birth' => $faker->optional()->dateTimeBetween('-60 years', '-18 years'),
        'hobby' => $faker->optional()->randomElements([
            'football',
            'hockey',
            'curling',
            'snowboarding',
            'skiing',
            'fishing',
            'cycling',
            'baseball',
            'basketball'
        ]),
        'geo_position' => [
            'lat' => $faker->latitude,
            'lng' => $faker->longitude
        ]
    ];
});
