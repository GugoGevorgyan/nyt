<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToClientFavoriteDriverTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('client_favorite_driver', function(Blueprint $table)
		{
			$table->foreign('client_id', 'client_favorite_driver_client_id')->references('client_id')->on('clients')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('driver_id', 'client_favorite_driver_driver_id')->references('driver_id')->on('drivers')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('client_favorite_driver', function(Blueprint $table)
		{
			$table->dropForeign('client_favorite_driver_client_id');
			$table->dropForeign('client_favorite_driver_driver_id');
		});
	}

}
