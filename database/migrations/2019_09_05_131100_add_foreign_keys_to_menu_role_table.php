<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 *
 */
class AddForeignKeysToMenuRoleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_role', function (Blueprint $table) {
            $table
                ->foreign('menu_id', 'menu_role_foreign_menu_id')
                ->references('menu_id')
                ->on('menus')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table
                ->foreign('role_id', 'menu_role_foreign_role_id')
                ->references('role_id')
                ->on('roles')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');

            $table
                ->foreign('permission_id', 'menu_role_foreign_permission_id')
                ->references('permission_id')
                ->on('permissions')
                ->onUpdate('NO ACTION')
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
        Schema::table('menu_role', function (Blueprint $table) {
            $table->dropForeign('menu_role_foreign_menu_id');
            $table->dropForeign('menu_role_foreign_role_id');
            $table->dropForeign('menu_role_foreign_permission_id');
        });
    }

}
