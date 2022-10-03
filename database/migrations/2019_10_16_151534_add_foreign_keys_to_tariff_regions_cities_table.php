<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToTariffRegionsCitiesTable
 */
class AddForeignKeysToTariffRegionsCitiesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tariff_regions_cities', function (Blueprint $table) {
            $table->foreign('tariff_id', 'tariff_cities_tariff_id_foreign')
                ->references('tariff_id')
                ->on('tariffs')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('area_id', 'tariff_cities_area_id_foreign')
                ->references('area_id')
                ->on('areas')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('sit_type_id', 'tariff_cities_sit_type_id_foreign')
                ->references('tariff_type_id')
                ->on('tariff_price_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('price_type_id', 'tariff_cities_price_type_id_foreign')
                ->references('tariff_type_id')
                ->on('tariff_price_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tariff_cities', function (Blueprint $table) {
            $table->dropForeign('tariff_cities_tariff_id_foreign');
            $table->dropForeign('tariff_cities_area_id_foreign');
            $table->dropForeign('tariff_cities_sit_type_id_foreign');
            $table->dropForeign('tariff_cities_price_type_id_foreign');
        });
    }

}
