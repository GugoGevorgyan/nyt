<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToTariffsTable
 */
class AddForeignKeysToTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tariffs', static function (Blueprint $table) {
            $table->foreign('tariff_type_id', 'tariffs_tariff_type_id_foreign')
                ->references('tariff_type_id')
                ->on('tariff_price_types')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('car_class_id', 'tariffs_car_class_id_foreign')
                ->references('car_class_id')
                ->on('cars_class')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('country_id', 'tariffs_country_foreign')
                ->references('country_id')
                ->on('countries')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('payment_type_id', 'tariffs_payment_type_foreign')
                ->references('payment_type_id')
                ->on('payment_types')
                ->onUpdate('NO ACTION')
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
        Schema::table('tariffs', static function (Blueprint $table) {
            $table->dropForeign('tariffs_tariff_type_id_foreign');
            $table->dropForeign('tariff_car_class_id_foreign');
            $table->dropForeign('tariffs_country_foreign');
            $table->dropForeign('tariff_region_id_id_foreign');
            $table->dropForeign('tariffs_payment_type_foreign');
        });
    }
}
