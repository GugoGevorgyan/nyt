<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarCrashImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_crash_images', function (Blueprint $table) {
            $table->increments('car_crash_image_id')->index('index_car_crash_image_id');
            $table->string('name');
            $table->integer('car_crash_id')->unsigned()->nullable()->index('images_car_crash_id');
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crash_images');
    }
}
