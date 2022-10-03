<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdForeignKeysToDriversInfoTable
 */
class AdForeignKeysToDriversInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('drivers_info', static function (Blueprint $table) {
            $table
                ->foreign('franchise_id', 'drivers_info_foreign_franchise_id')
                ->references('franchise_id')
                ->on('franchisee')
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
        Schema::table('drivers_info', static function (Blueprint $table) {
            $table->dropForeign('drivers_info_foreign_franchise_id');
        });
    }
}
