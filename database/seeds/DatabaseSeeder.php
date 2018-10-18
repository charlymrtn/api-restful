<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;

use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate():

        $cant_users = 100;
        $cant_categories = 20;
        $cant_products = 500;
        $cant_transactions = 500;

        factory(User::class, $cant_users)->create();
        factory(Category::class, $cant_categories)->create();

        factory(Product::class, $cant_products)->create()->each(
          function($product){
            $categories = Category::all()->random(mt_rand(1,5))->pluck('uuid');

            $product->categories()->attach($categories);
          }
        );
        
        factory(Transaction::class, $cant_transactions)->create();
    }
}
