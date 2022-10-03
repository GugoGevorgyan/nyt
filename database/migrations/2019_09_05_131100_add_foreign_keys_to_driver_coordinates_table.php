<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDriverCoordinatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('driver_coordinates', function(Blueprint $table)
		{
			$table->foreign('driver_id', 'driver_coordinates_driver_id')->references('driver_id')->on('drivers')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('driver_coordinates', function(Blueprint $table)
		{
			$table->dropForeign('driver_coordinates_driver_id');
		});
	}

}
