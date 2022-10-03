<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdForeignKeysToFranchiseTransactionsTable
 */
class AdForeignKeysToFranchiseTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('franchise_transactions', function (Blueprint $table) {
            $table->foreign('franchise_id', 'franchise_transactions_franchise_id_foreign')
                ->references('franchise_id')
                ->on('franchisee')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('park_id', 'franchise_transactions_park_id_foreign')
                ->references('park_id')
                ->on('parks')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('worker_id', 'franchise_transactions_worker_id_foreign')
                ->references('system_worker_id')
                ->on('system_workers')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table->foreign('payment_type_id', 'franchise_transactions_payment_type_id_foreign')
                ->references('payment_type_id')
                ->on('payment_types')
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
        Schema::table('franchise_transactions', function (Blueprint $table) {
            $table->dropForeign('franchise_transactions_franchise_id_foreign');
            $table->dropForeign('franchise_transactions_park_id_foreign');
            $table->dropForeign('franchise_transactions_worker_id_foreign');
            $table->dropForeign('franchise_transactions_payment_type_id_foreign');
        });
    }
}
