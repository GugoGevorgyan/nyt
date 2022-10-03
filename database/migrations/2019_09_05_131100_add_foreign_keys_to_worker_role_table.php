<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class AddForeignKeysToWorkerRoleTable
 */
class AddForeignKeysToWorkerRoleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('worker_role', function(Blueprint $table)
		{
			$table->foreign('role_id', 'worker_role_id')
                ->references('role_id')
                ->on('roles')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
			$table->foreign('system_worker_id', 'worker_role_worker_id')
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
		Schema::table('worker_role', function(Blueprint $table)
		{
			$table->dropForeign('worker_role_id');
			$table->dropForeign('worker_role_worker_id');
		});
	}

}
