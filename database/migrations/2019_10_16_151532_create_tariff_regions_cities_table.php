<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateTariffRegionsCitiesTable
 */
class CreateTariffRegionsCitiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'tariff_regions_cities',
            static function (Blueprint $table) {
                $table->increments('tariff_region_city_id')->index('index_tariff_regions_cities_tariff_id');
                $table->unsignedInteger('tariff_id')->index('tariff_regions_cities_tariff_id');
                $table->unsignedInteger('area_id')->nullable()->index('tariff_regions_cities_area_id');
                $table->unsignedInteger('price_type_id')->index('tariff_regions_cities_price_type_id');
                $table->unsignedInteger('sit_type_id')->index('tariff_regions_cities_sit_type_id');

                $table->decimal('price_km')->nullable();
                $table->decimal('price_min')->default(0)->nullable();
                $table->decimal('cancel_fee')->default(0);

                $table->boolean('sitting_fee')->default(0)->nullable();
                $table->decimal('sit_fix_price')->default(0)->nullable();
                $table->decimal('sit_price_km')->default(0)->nullable();
                $table->decimal('sit_price_minute')->default(0)->nullable();

                $table->unsignedFloat('minimal_distance_value', 5, 1)->default(0)->comment('KM');
                $table->unsignedSmallInteger('minimal_duration_value')->default(0)->comment('MINUTE');

                $table->unsignedTinyInteger('free_wait_stop_minutes')->nullable();
                $table->decimal('paid_wait_stop_minute')->nullable();
                $table->unsignedSmallInteger('change_initial_price_percent')->nullable();
                $table->boolean('merge_km_minute')->default(false);

                $table->boolean('enable_speed_wait')->default(0)->nullable();
                $table->unsignedTinyInteger('speed_wait_limit')->default(0)->nullable();
                $table->decimal('speed_wait_price_minute')->default(0)->nullable();

                $table->timestamps(6);
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
        Schema::drop('tariff_cities');
    }

}
