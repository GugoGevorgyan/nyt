<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToFranchiseeModuleTable
 */
class AddForeignKeysToFranchiseeModuleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_module', function (Blueprint $table) {
            $table->foreign('module_id', 'franchise_modules_module_id')
                ->references('module_id')
                ->on('modules')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign('franchise_id', 'franchise_module_franchise_id')
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
        Schema::table('franchise_module', function (Blueprint $table) {
            $table->dropForeign('franchise_modules_module_id');
            $table->dropForeign('franchise_module_franchise_id');
        });
    }

}
