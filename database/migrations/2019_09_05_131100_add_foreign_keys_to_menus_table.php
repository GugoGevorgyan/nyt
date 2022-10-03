<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menus', function(Blueprint $table)
		{
			$table->foreign('parent_id', 'menus_parent_id')->references('menu_id')->on('menus')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('route_id', 'menus_route_id')->references('route_id')->on('routes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('menus', function(Blueprint $table)
		{
			$table->dropForeign('menus_parent_id');
			$table->dropForeign('menus_route_id');
		});
	}

}
