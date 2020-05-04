<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_items', function (Blueprint $table) {
            
        $table->unsignedBigInteger('delivery_id')
                ->nullable();

        $table->foreign('delivery_id')
                ->references('id')
                ->on('deliveries')
                ->onDelete('cascade');

        $table->unsignedBigInteger('product_id')
                ->nullable();

        $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

        $table->integer('quantity')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_items');
    }
}
