<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWorkerRatingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('worker_rating', function(Blueprint $table)
		{
			$table->foreign('worker_id', 'worker_rating_worker_id')->references('system_worker_id')->on('system_workers')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('worker_rating', function(Blueprint $table)
		{
			$table->dropForeign('worker_rating_worker_id');
		});
	}

}
