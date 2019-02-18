<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $title =$faker->sentence(4); // crea una oracion cualquiera en 4 palabras
    return [
    	'user_id' => rand(1, 30),
    	'category_id' => rand(1, 20),
        'name' => $title,
        'slug' => str_slug($title), // Helper de laravel para convertir cualquier string a slug
        'excerpt' =>$faker->text(200),
        'body' => $faker->text(500), // faker es quien crea por nosotros la informacion falsa
        'file' =>$faker->imageUrl($width = 1200, $height = 400),
        'status' =>$faker->randomElement(['DRAFT', 'PUBLISHED']),
    ];
});
