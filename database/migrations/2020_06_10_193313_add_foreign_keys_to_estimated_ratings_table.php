<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToEstimatedRatingsTable
 */
class AddForeignKeysToEstimatedRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimated_ratings', static function (Blueprint $table) {

            $table->foreign('order_id', 'estimated_ratings_foreign_order_id')
                ->references('order_id')
                ->on('orders')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('driver_id', 'estimated_ratings_foreign_driver_id')
                ->references('driver_id')
                ->on('drivers')
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
        Schema::table('estimated_ratings', static function (Blueprint $table) {
            $table->dropForeign('estimated_ratings_foreign_order_id');
            $table->dropForeign('estimated_ratings_foreign_driver_id');
        });
    }
}
