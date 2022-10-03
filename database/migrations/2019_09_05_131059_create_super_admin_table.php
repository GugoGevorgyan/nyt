<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSuperAdminTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('super_admin', function(Blueprint $table)
		{
			$table->increments('super_admin_id')->index('index_super_admin_id');
			$table->string('name', 100)->nullable();
			$table->string('email', 150)->nullable();
			$table->string('remember_token', 150)->nullable();
			$table->string('password', 250)->nullable();
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
		Schema::drop('super_admin');
	}

}
