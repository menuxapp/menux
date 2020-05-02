<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name', 50);
            $table->string('email', 50);
            $table->string('password');
            $table->rememberToken();

            $table->unsignedBigInteger('store_id')
                    ->nullable();

            $table->foreign('store_id')
                        ->references('id')
                        ->on('stores')
                        ->onDelete('cascade');

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
        Schema::dropIfExists('users');
    }
}
