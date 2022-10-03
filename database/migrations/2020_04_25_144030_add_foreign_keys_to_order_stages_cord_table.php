<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderStagesCordTable
 */
class AddForeignKeysToOrderStagesCordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_stages_cord', function (Blueprint $table) {
            $table->foreign('order_id', 'order_stages_cord_foreign_order_id')
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
        Schema::table('order_stages_cord', function (Blueprint $table) {
            $table->dropForeign('order_stages_cord_foreign_order_id');
        });
    }
}
