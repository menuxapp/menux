<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();

            $table->string('name', 50);
            $table->string('cep', 11);
            $table->string('address', 50);
            $table->string('address_number', 50);
            $table->string('district', 50);
            $table->string('address_comp', 20)->nullable();
            $table->string('city', 30);
            $table->string('uf', 2);
            $table->string('state', 30)->nullable();
            $table->integer('status')->default(0);

            $table->softDeletes('deleted_at', 0);

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
        Schema::dropIfExists('stores');
    }
}
