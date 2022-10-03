<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToOrderProcessesTable
 */
class AddForeignKeysToOrderProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('order_processes', static function (Blueprint $table) {
            $table
                ->foreign('order_shipped_id', 'driver_info_license_type_foreign_order_shipped_id')
                ->references('order_shipped_driver_id')
                ->on('order_shipped_drivers')
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
        Schema::table('order_processes', static function (Blueprint $table) {
            $table->dropForeign('driver_info_license_type_foreign_order_shipped_id');
        });
    }
}
