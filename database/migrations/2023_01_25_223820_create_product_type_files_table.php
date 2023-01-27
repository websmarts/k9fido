<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTypeFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_type_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('typeid')->unsigned();
            $table->string('filename');
            $table->string('filepath');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->integer('order')->nullable();
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
        Schema::drop('product_type_files');
    }
}
