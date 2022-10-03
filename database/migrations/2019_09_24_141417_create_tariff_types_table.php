<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateTariffTypesTable
 */
class CreateTariffTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
    {
		Schema::create('tariff_price_types', function(Blueprint $table)
		{
			$table->increments('tariff_type_id')->index('index_tariff_type_id');
			$table->string('name')->unique('tariff_type_name_uindex');
			$table->tinyInteger('type');
			$table->boolean('status')->default(1);
			$table->timestamp('created_at');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
    {
		Schema::drop('tariff_types');
	}

}
