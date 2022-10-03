<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToParkMechanicTable
 */
class AddForeignKeysToParkMechanicTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('park_mechanic', function(Blueprint $table)
		{
			$table
                ->foreign('mechanic_id', 'park_mechanic_mechanic_id')
                ->references('system_worker_id')
                ->on('system_workers')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
			$table
                ->foreign('park_id', 'park_mechanic_park_id')
                ->references('park_id')
                ->on('parks')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('park_mechanic', function(Blueprint $table)
		{
			$table->dropForeign('park_mechanic_mechanic_id');
			$table->dropForeign('park_mechanic_park_id');
		});
	}

}
