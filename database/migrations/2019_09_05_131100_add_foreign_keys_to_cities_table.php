<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('cities', function(Blueprint $table)
		{
			$table->foreign('country_id')->references('country_id')->on('countries')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('region_id')->references('region_id')->on('regions')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('cities', function(Blueprint $table)
		{
			$table->dropForeign('cities_country_id_foreign');
			$table->dropForeign('cities_region_id_foreign');
		});
	}

}
