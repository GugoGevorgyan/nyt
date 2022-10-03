<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class AddForeignKeysToExternalOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('external_orders', function (Blueprint $table) {
            $table->foreign('order_id', 'external_orders_foreign_order_id')
                ->references('order_id')
                ->on('orders')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('board_id', 'external_orders_foreign_board_id')
                ->references('external_board_id')
                ->on('external_boards')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('external_orders', function (Blueprint $table) {
            $table->dropForeign('external_orders_foreign_order_id');
            $table->dropForeign('external_orders_foreign_board_id');
        });
    }
}
