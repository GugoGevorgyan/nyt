<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateTariffRegionBehindTable
 */
class CreateTariffRegionBehindTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'tariff_region_behind',
            static function (Blueprint $table) {
                $table->increments('tariff_region_behind_id')->index('index_tariff_fictional_behind_id');
                $table->unsignedInteger('tariff_region_id')->nullable();
                $table->unsignedInteger('tariff_id')->nullable();
                $table->unsignedInteger('price_type_id');
                $table->unsignedInteger('sit_type_id');

                $table->unsignedFloat('zone_distance', 5, 1);
                $table->decimal('price_km');
                $table->decimal('price_min');
                $table->decimal('cancel_fee')->default(0);

                $table->boolean('sitting_fee')->default(0)->nullable();
                $table->decimal('sit_price_km')->default(0)->nullable();
                $table->decimal('sit_fix_price')->default(0)->nullable();
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
    public function down(): void
    {
        Schema::drop('tariff_region_behind');
    }

}
