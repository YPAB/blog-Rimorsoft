<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {

	$title =$faker->sentence(4); // crea una oracion cualquiera en 4 palabras
    return [
        'name' => $title,
        'slug' => str_slug($title), // Helper de laravel para convertir cualquier string a slug
        'body' => $faker->text(500), // faker es quien crea por nosotros la informacion falsa
    ];
});
