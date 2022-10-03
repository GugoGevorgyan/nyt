<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToOrderCorporatesTable
 */
class AddForeignKeysToOrderCorporatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_corporates', function (Blueprint $table) {
            $table->foreign('order_id', 'order_corporates_foreign_order_id')
                ->references('order_id')
                ->on('orders')
                ->onUpdate('CASCADE')
                ->onDelete('NO ACTION');

            $table->foreign('company_id', 'order_corporates_foreign_company_id')
                ->references('company_id')
                ->on('companies')
                ->onUpdate('CASCADE')
                ->onDelete('NO ACTION');

            $table->foreign('driver_id', 'order_corporates_foreign_driver_id')
                ->references('driver_id')
                ->on('drivers')
                ->onUpdate('CASCADE')
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
        Schema::table('order_corporates', function (Blueprint $table) {
            $table->dropForeign('order_corporates_foreign_order_id');
            $table->dropForeign('order_corporates_foreign_company_id');
            $table->dropForeign('order_corporates_foreign_driver_id');
        });
    }
}
