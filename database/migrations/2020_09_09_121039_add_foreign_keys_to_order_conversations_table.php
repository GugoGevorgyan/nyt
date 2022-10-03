<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderConversationsTable
 */
class AddForeignKeysToOrderConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'order_conversations',
            static function (Blueprint $table) {
                $table->foreign('order_id', 'order_conversations_foreign_order_id')
                    ->references('order_id')
                    ->on('orders')
                    ->onUpdate('NO ACTION')
                    ->onDelete('NO ACTION');

                $table->foreign('driver_id', 'order_conversations_foreign_driver_id')
                    ->references('driver_id')
                    ->on('drivers')
                    ->onUpdate('NO ACTION')
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
            'driver_order_conversation',
            static function (Blueprint $table) {
                $table->dropForeign('order_conversations_foreign_order_id');
                $table->dropForeign('order_conversations_foreign_driver_id');
            }
        );
    }
}
