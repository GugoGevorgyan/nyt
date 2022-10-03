<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class AddForeignKeysToOrderRentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_rent', function (Blueprint $table) {
            $table->foreign('order_id', 'order_rent_order_id_foreign')
                ->references('order_id')
                ->on('orders')
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
        Schema::table('order_rent', function (Blueprint $table) {
            $table->dropForeign('order_rent_tariff_id_foreign');
        });
    }
}
