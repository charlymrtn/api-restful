<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
          $table->uuid('uuid')->primary();

          $table->unsignedInteger('quantity');

          $table->uuid('buyer_uuid');
          $table->uuid('product_uuid');

          $table->timestamps();
          $table->softDeletes();

          $table->index(['buyer_uuid','product_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
