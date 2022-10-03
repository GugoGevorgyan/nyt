<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateDriverCoordinatesTable
 */
class CreateDriverCoordinatesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_coordinates', function (Blueprint $table) {
            $table->increments('driver_coordinate_id')->index('index_driver_coordinate_id');
            $table->unsignedInteger('driver_id')->index('driver_coordinates_driver_id');
            $table->json('coordinates');
            $table->date('date');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('driver_coordinates');
    }

}
