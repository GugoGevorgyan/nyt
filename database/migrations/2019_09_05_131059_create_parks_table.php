<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateParksTable
 */
class CreateParksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('parks', function (Blueprint $table) {
            $table->increments('park_id')->index('index_park_id');
            $table->unsignedInteger('franchise_id')->nullable()->index('parks_franchise_id');
            $table->unsignedInteger('manager_id')->nullable()->index('parks_manager_id');
            $table->unsignedInteger('entity_id')->index('park_entity_id');
            $table->string('name',150)->nullable();
            $table->integer('city_id')->nullable()->index('parks_city_id');
            $table->string('address',300)->nullable();
            $table->string('image',300)->nullable();

            $table->softDeletes();
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
        Schema::drop('parks');
    }
}
