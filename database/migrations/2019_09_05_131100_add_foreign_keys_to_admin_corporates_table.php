<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToAdminCorporatesTable
 */
class AddForeignKeysToAdminCorporatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('admin_corporates', function(Blueprint $table)
		{
			$table->foreign('franchise_id', 'admin_corporate_franchise_id')
                ->references('franchise_id')
                ->on('franchisee')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('admin_corporates', function(Blueprint $table)
		{
			$table->dropForeign('admin_corporate_franchise_id');
		});
	}

}
