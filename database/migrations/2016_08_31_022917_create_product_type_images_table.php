<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductTypeImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producttypeimages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('typeid')->unsigned();
            $table->string('filename');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->float('order')->nullable();
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
        Schema::drop('producttypeimages');
    }
}
