<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderMeetsTable
 */
class AddForeignKeysToOrderMeetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'order_meets',
            static function (Blueprint $table) {
                $table->foreign('order_id', 'order_meets_foreign_order_id')
                    ->references('order_id')
                    ->on('orders')
                    ->onUpdate('CASCADE')
                    ->onDelete('NO ACTION');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(
            'order_meets',
            static function (Blueprint $table) {
                $table->dropForeign('order_meets_foreign_order_id');
            }
        );
    }
}
