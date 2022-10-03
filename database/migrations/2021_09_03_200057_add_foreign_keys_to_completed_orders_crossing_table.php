<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 */
class AddForeignKeysToCompletedOrdersCrossingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('completed_orders_crossing', function (Blueprint $table) {
            $table->foreign('completed_id', 'completed_orders_crossing_foreign_completed_id')
                ->references('completed_order_id')
                ->on('completed_orders')
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
        Schema::table('completed_orders_crossing', function (Blueprint $table) {
            $table->dropForeign('completed_orders_crossing_foreign_completed_id');
        });
    }
}
