<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Src\Repositories\Menu\MenuContract;

/**
 * Class CreateMenusTable
 */
class CreateMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menus', function(Blueprint $table)
		{
			$table->increments('menu_id')->index('index_menu_id');
			$table->integer('route_id')->unsigned()->nullable()->index('menus_route_id');
			$table->integer('parent_id')->unsigned()->nullable()->index('menus_parent_id');
			$table->string('title', 100)->nullable();
			$table->string('description', 300)->nullable();
			$table->string('icon', 64)->nullable();
            $table->timestamps(6);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('menus');
	}

}
