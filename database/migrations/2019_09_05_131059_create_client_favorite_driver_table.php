<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateClientFavoriteDriverTable
 */
class CreateClientFavoriteDriverTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
    {
		Schema::create('client_favorite_driver', function(Blueprint $table)
		{
			$table->increments('client_favorite_driver')->index('index_client_favorite_driver');
			$table->integer('client_id')->unsigned()->nullable()->index('client_driver_client_id');
			$table->integer('driver_id')->unsigned()->nullable()->index('client_driver_driver_id');
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
		Schema::drop('client_favorite_driver');
	}

}
