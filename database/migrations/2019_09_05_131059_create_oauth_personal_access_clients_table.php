<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOauthPersonalAccessClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oauth_personal_access_clients', function(Blueprint $table)
		{
			$table->increments('id')->index('index_oauth_personal_access_clients_id');
			$table->integer('client_id')->unsigned()->index();
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
		Schema::drop('oauth_personal_access_clients');
	}

}
