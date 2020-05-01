<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();

            $table->string('name', 50);

            $table->string('cep', 11);
            $table->string('address', 50);
            $table->string('address_number', 10);
            $table->string('district', 50);
            $table->string('complement', 50);
            $table->string('city', 50);
            $table->string('state', 50);
            $table->string('category', 50);
            $table->integer('status');


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
        Schema::dropIfExists('establishments');
    }
}
