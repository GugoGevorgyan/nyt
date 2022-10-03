<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToPreordersTable
 */
class AddForeignKeysToPreordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'preorders',
            static function (Blueprint $table) {
                $table->foreign('order_id', 'orders_schedule_times_foreign_order_id')
                    ->references('order_id')
                    ->on('orders')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'orders_schedule_times',
            static function (Blueprint $table) {
                $table->dropForeign('orders_schedule_times_foreign_order_id');
            }
        );
    }
}
