<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateParkMechanicTable
 */
class CreateParkMechanicTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('park_mechanic', function(Blueprint $table)
		{
			$table->increments('park_mechanic_id')->index('index_park_mechanic_id');
			$table->unsignedInteger('park_id')->nullable()->index('park_mechanic_park_id');
			$table->unsignedInteger('mechanic_id')->nullable()->index('park_mechanic_mechanic_id')->comment('system_worker_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('park_mechanic');
	}

}
