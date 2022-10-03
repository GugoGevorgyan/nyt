<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToTariffRegionBehindTable
 */
class AddForeignKeysToTariffRegionBehindTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('tariff_region_behind', static function (Blueprint $table) {

            $table->foreign('tariff_region_id', 'tariff_behind_fictional_tariff_region_city_id_foreign')
                ->references('tariff_region_city_id')
                ->on('tariff_regions_cities')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('sit_type_id', 'tariff_behind_sit_type_id_foreign')
                ->references('tariff_type_id')
                ->on('tariff_price_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('price_type_id', 'tariff_behind_price_type_id_foreign')
                ->references('tariff_type_id')
                ->on('tariff_price_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('tariff_id', 'tariff_behind_tariff_id_foreign')
                ->references('tariff_id')
                ->on('tariffs')
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
        Schema::table('tariff_fictional_behind', function (Blueprint $table) {
            $table->dropForeign('tariff_behind_fictional_tariff_region_city_id_foreign');
            $table->dropForeign('tariff_behind_sit_type_id_foreign');
            $table->dropForeign('tariff_behind_price_type_id_foreign');
            $table->dropForeign('tariff_behind_tariff_id_foreign');
        });
    }

}
