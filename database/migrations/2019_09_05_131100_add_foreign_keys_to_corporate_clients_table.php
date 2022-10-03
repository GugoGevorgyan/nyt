<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCorporateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('corporate_clients', function(Blueprint $table)
		{
			$table->foreign('client_id', 'client_id')->references('client_id')->on('clients')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('company_id', 'company_id')->references('company_id')->on('companies')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('corporate_clients', function(Blueprint $table)
		{
			$table->dropForeign('client_id');
			$table->dropForeign('company_id');
		});
	}

}
