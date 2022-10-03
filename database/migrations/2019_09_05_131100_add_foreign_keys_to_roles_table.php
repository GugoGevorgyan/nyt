<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('roles', function(Blueprint $table)
		{
			$table->foreign('homepage_route_id', 'roles_id')->references('route_id')->on('routes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('module_id', 'roles_module_id')->references('module_id')->on('modules')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('roles', function(Blueprint $table)
		{
			$table->dropForeign('roles_id');
			$table->dropForeign('roles_module_id');
		});
	}

}
