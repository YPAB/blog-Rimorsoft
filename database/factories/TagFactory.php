<?php

use Faker\Generator as Faker;

$factory->define(App\Tag::class, function (Faker $faker) {

   $title =$faker->unique()->word(5); // que sea unico y que sea una palabra de 5 caracteres
    return [
        'name' => $title,
        'slug' => str_slug($title), // Helper de laravel para convertir cualquier string a slug
    ];
});
