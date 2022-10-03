<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToCompletedOrderChangesTable
 */
class AddForeignKeysToCompletedOrderChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('completed_order_changes', function (Blueprint $table) {
            $table->foreign('changer_id', 'completed_order_changes_foreign_changer_id')
                ->references('system_worker_id')
                ->on('system_workers')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('completed_id', 'completed_order_changes_foreign_completed_id')
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
        Schema::table('completed_order_changes', function (Blueprint $table) {
            $table->dropForeign('completed_order_changes_foreign_changer_id');
            $table->dropForeign('completed_order_changes_foreign_completed_id');
        });
    }
}
