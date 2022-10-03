<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateDriverTypesTable
 */
class CreateDriverTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'driver_types',
            function (Blueprint $table) {
                $table->increments('driver_type_id')->index('index_driver_type_id');
                $table->string('type', 120);
                $table->string('name', 150);
                $table->text('description')->nullable();
                $table->text('image')->nullable();
                $table->boolean('worked')->default(1);

                $table->timestamps(6);
            }
        );
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('drivers_types');
    }

}
