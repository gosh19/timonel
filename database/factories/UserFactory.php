<?php

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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => 'Isaias Diaz',
        'email' => 'isaarg2312@gmail.com',
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'profile_image' => 'dist/users/default.png',
        'ip_client'=>"1",
        'descripcion_puesto'=>"Desarrollador Web",
        'id_categoria' => 1,
        'empresa_id'=> 1
    ];
});
