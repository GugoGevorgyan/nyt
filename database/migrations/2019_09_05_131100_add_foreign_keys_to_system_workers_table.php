<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSystemWorkersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('system_workers', function(Blueprint $table)
		{
			$table->foreign('franchise_id', 'system_workers_franchise_id')
                ->references('franchise_id')
                ->on('franchisee')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
			$table->foreign('graphic_id', 'system_workers_graphic_id')
                ->references('worker_graphic_id')
                ->on('workers_graphic')
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
		Schema::table('system_workers', function(Blueprint $table)
		{
			$table->dropForeign('system_workers_franchise_id');
			$table->dropForeign('system_workers_graphic_id');
		});
	}

}
