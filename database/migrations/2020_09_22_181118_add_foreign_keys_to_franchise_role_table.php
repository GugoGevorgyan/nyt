<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFranchiseRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_role', function (Blueprint $table) {
            $table
                ->foreign('franchise_id', 'franchise_role_foreign_franchise_id')
                ->references('franchise_id')
                ->on('franchisee')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('franchise_module_id', 'franchise_role_foreign_franchise_module_id')
                ->references('franchise_module_id')
                ->on('franchise_module')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('role_id', 'franchise_role_foreign_role_id')
                ->references('role_id')
                ->on('roles')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_role', function (Blueprint $table) {
            $table->dropForeign('franchise_role_foreign_franchise_id');
            $table->dropForeign('franchise_role_foreign_franchise_module_id');
            $table->dropForeign('franchise_role_foreign_role_id');
        });
    }
}
