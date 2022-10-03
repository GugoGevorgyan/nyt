<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCitiesTable
 */
class CreateCitiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('city_id')->index('index_city_id');
            $table->unsignedInteger('region_id')->index('cities_region_id_index');
            $table->unsignedInteger('country_id')->index('cities_country_id_index');
            $table->string('name')->index('cities_name_index');
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
        Schema::drop('cities');
    }

}
