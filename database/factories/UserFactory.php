<?php

use Faker\Generator as Faker;

use App\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;

use App\Models\Seller;
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
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123'), // secret
        'remember_token' => str_random(10),
        'verified' => $verificado =$faker->randomElement([User::USUARIO_VERIFICADO,User::USUARIO_NO_VERIFICADO]),
        'verification_token' => $verificado == User::USUARIO_VERIFICADO ? User::generateToken() : null,
        'admin' => $faker->randomElement([User::USUARIO_ADMINISTRADOR,User::USUARIO_NO_ADMINISTRADOR])
    ];
});

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1)
    ];
});

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $quantity = $faker->numberBetween(1,10),
        'status' => $quantity >= 0 ? Product::PRODUCTO_DISPONIBLE : Product::PRODUCTO_NO_DISPONIBLE,
        'image' => $faker->randomElement(['product1.jpg','product2.jpg','product3.jpg']),
        'seller_uuid' => User::all()->random()->uuid
    ];
});

$factory->define(Transaction::class, function (Faker $faker) {

    $seller = Seller::has('products')->get()->random();
    $buyer = User::all()->except($seller->uuid)->random();

    return [
        'quantity' => $faker->numberBetween(1,3),
        'buyer_uuid' =>  $buyer->uuid,
        'product_uuid' =>  $seller->products->random()->uuid,
    ];
});
