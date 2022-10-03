<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderInitialDataTable
 */
class AddForeignKeysToOrderInitialDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_initial_data', static function (Blueprint $table) {
            $table->foreign('initial_tariff_id', 'foreign_initial_order_data_initial_tariff_id')
                ->references('tariff_id')
                ->on('tariffs')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('second_tariff_id', 'foreign_second_order_data_initial_tariff_id')
                ->references('tariff_id')
                ->on('tariffs')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('order_id', 'foreign_initial_order_data_order_id')
                ->references('order_id')
                ->on('orders')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('region_id', 'foreign_initial_order_data_region_id')
                ->references('region_id')
                ->on('regions')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('city_id', 'foreign_initial_order_data_city_id')
                ->references('city_id')
                ->on('cities')
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
        Schema::table('initial_order_data', function (Blueprint $table) {
            $table->dropForeign('foreign_initial_order_data_initial_tariff_id');
            $table->dropForeign('foreign_second_order_data_initial_tariff_id');
            $table->dropForeign('foreign_initial_order_data_order_id');
            $table->dropForeign('foreign_initial_order_data_region_id');
            $table->dropForeign('foreign_initial_order_data_city_id');
        });
    }
}
