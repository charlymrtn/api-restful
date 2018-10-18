<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Product;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->string('name');
            $table->string('description',1000);
            $table->unsignedInteger('quantity');
            $table->string('status')->default(Product::PRODUCTO_NO_DISPONIBLE);
            $table->string('image');

            $table->uuid('seller_uuid');

            $table->timestamps();
            $table->softDeletes();

            $table->index('seller_uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
