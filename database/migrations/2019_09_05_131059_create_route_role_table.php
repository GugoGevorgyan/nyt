<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRouteRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('route_role', function(Blueprint $table)
		{
			$table->increments('route_role_id')->index('index_route_role_id');
			$table->integer('route_id')->unsigned()->nullable()->index('route_role_route_id');
			$table->integer('role_id')->unsigned()->nullable()->index('route_role_role_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('route_role');
	}

}
