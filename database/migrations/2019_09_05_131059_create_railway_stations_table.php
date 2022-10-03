<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateRailwayStationsTable
 */
class CreateRailwayStationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'railway_stations',
            function (Blueprint $table) {
                $table->increments('railway_station_id')->index('index_railway_station_id');
                $table->unsignedInteger('city_id')->index('railway_stations_index_city_id');
                $table->string('name', 200);
                $table->string('input', 200)->nullable();
                $table->string('address', 300)->nullable();
                $table->decimal('lat', 10, 8)->nullable()->index('railway_stations_index_latitude');
                $table->decimal('lut', 11, 8)->nullable()->index('railway_stations_index_longitude');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            }
        );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('railway_stations');
    }

}
