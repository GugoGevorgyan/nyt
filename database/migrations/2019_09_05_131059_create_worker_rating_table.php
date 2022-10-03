<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateWorkerRatingTable
 */
class CreateWorkerRatingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
    {
		Schema::create('worker_rating', function(Blueprint $table)
		{
			$table->increments('worker_rating_id')->index('index_worker_rating_id');
			$table->integer('worker_id')->unsigned()->nullable()->index('worker_rating_worker_id');
			$table->float('rating', 10, 0)->default(3)->nullable();
			$table->timestamps(6);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
    {
		Schema::drop('worker_rating');
	}

}
