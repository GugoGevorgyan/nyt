<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRouteRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('route_role', function(Blueprint $table)
		{
			$table->foreign('role_id', 'route_role_role_id')->references('role_id')->on('roles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('route_id', 'route_role_route_id')->references('route_id')->on('routes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('route_role', function(Blueprint $table)
		{
			$table->dropForeign('route_role_role_id');
			$table->dropForeign('route_role_route_id');
		});
	}

}
