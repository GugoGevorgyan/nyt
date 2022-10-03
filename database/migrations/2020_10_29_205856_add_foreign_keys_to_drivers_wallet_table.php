<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToDriversWalletTable
 */
class AddForeignKeysToDriversWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('drivers_wallet', function (Blueprint $table) {
            $table
                ->foreign('driver_id', 'drivers_cash_foreign_driver_id')
                ->references('driver_id')
                ->on('drivers')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('repayment_id', 'drivers_cash_foreign_repayment_id')
                ->references('debt_repayment_id')
                ->on('debt_repayment')
                ->onUpdate('CASCADE')
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
        Schema::table('drivers_cash', function (Blueprint $table) {
            $table->dropForeign('drivers_cash_foreign_driver_id');
            $table->dropForeign('drivers_cash_foreign_repayment_id');
        });
    }
}
