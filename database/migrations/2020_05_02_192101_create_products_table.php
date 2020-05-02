<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();

            $table->string('name');
            
            $table->unsignedBigInteger('product_category');

            $table->foreign('product_category')
                        ->references('id')
                        ->on('product_categories')
                        ->onDelete('cascade');

            $table->string('description');
            $table->float('value', 8, 2);
            $table->boolean('status')->default(1);
            $table->string('image');

            $table->timestamps();
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
