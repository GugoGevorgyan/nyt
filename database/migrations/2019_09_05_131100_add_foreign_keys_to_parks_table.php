<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToParksTable
 */
class AddForeignKeysToParksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('parks', function(Blueprint $table)
		{
			$table->foreign('franchise_id', 'parks_franchise_id')
                ->references('franchise_id')
                ->on('franchisee')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
			$table->foreign('manager_id', 'parks_manager_id')
                ->references('system_worker_id')
                ->on('system_workers')
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
		Schema::table('parks', function(Blueprint $table)
		{
			$table->dropForeign('parks_franchise_id');
			$table->dropForeign('parks_manager_id');
		});
	}

}
