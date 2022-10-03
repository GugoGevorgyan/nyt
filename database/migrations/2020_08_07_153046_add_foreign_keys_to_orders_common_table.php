<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrdersCommonTable
 */
class AddForeignKeysToOrdersCommonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('orders_common', static function (Blueprint $table) {
            $table->foreign('order_id', 'orders_common_foreign_order_id')
                ->references('order_id')
                ->on('orders')
                ->onUpdate('CASCADE')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('common_orders', static function (Blueprint $table) {
            $table->dropForeign('orders_common_foreign_order_id');
        });
    }
}
