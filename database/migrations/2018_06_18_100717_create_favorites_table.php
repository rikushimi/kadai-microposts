<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('u_id')->unsigned()->index();
            $table->integer('fav_id')->unsigned()->index();
            $table->timestamps();
            
            $table->foreign('u_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('fav_id')->references('id')->on('microposts')->onDelete('cascade');
            
             $table->unique(['u_id', 'fav_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
