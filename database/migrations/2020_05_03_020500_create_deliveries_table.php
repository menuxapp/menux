<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('store_id')
                    ->nullable();

            $table->foreign('store_id')
                        ->references('id')
                        ->on('stores')
                        ->onDelete('cascade');

            $table->string('number_client')->nullable();

            $table->integer('payment_method');

            $table->float('value', 8, 2);
            $table->float('amount_paid', 8, 2);

            $table->string('address_cep')->nullable();

            $table->string('address_number')->nullable();

            $table->integer('status')->default(0);
            
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
        Schema::dropIfExists('deliveries');
    }
}
