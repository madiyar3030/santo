<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->enum('feeding_type', ['recipe', 'breastfeeding']);
            $table->string('description');
            $table->bigInteger('category_id')->unsigned();
            $table->integer('age_from')->nullable();
            $table->integer('age_to');
            $table->enum('age_type', ['day', 'week', 'month', 'year']);
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('feeding_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedings');
    }
}
