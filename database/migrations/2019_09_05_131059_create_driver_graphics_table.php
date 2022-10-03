<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDriverGraphicsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('driver_graphics', function(Blueprint $table)
		{
			$table->increments('driver_graphic_id')->index('index_driver_graphic_id');
			$table->boolean('type')->nullable();
			$table->string('name', 50)->nullable();
			$table->string('description', 500)->nullable();
			$table->smallInteger('working_days_count')->nullable();
			$table->smallInteger('weekend_days_count')->nullable();
			$table->json('week');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('driver_graphics');
	}

}
