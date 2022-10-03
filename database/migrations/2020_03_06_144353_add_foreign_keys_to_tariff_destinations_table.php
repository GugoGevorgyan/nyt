<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToTariffDestinationsTable
 */
class AddForeignKeysToTariffDestinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tariff_destinations', static function (Blueprint $table) {
            $table->foreign('tariff_id', 'destinations_tariff_id')
                ->references('tariff_id')
                ->on('tariffs')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('price_type_id', 'destinations_price_tariff_id')
                ->references('tariff_type_id')
                ->on('tariff_price_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('destination_from_id', 'destinations_destination_from_id')
                ->references('area_id')
                ->on('areas')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign('destination_to_id', 'destinations_destination_to_id')
                ->references('area_id')
                ->on('areas')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('destinations', static function (Blueprint $table) {
            $table->dropForeign('destinations_tariff_id');
            $table->dropForeign('destinations_destination_from_id');
            $table->dropForeign('destinations_destination_to_id');
            $table->dropForeign('destinations_price_tariff_id');
        });
    }
}
