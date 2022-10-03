<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDriverCandidatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('driver_candidates', function(Blueprint $table)
		{
			$table->increments('driver_candidate_id')->index('index_driver_candidate_id');
            $table->integer('driver_info_id')->unsigned()->nullable()->index('driver_info_id');
            $table->integer('franchise_id')->unsigned()->nullable()->index('candidate_franchise_id');
			$table->integer('tutor_id')->unsigned()->nullable()->index('candidate_tutor_id');
			$table->bigInteger('phone')->nullable();
			$table->integer('learn_status_id');
			$table->date('learn_start')->nullable();
			$table->date('learn_end')->nullable();
			$table->softDeletes();
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
		Schema::drop('driver_candidates');
	}

}
