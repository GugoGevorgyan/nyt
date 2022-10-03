<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDriverSubtypesTable
 */
class CreateDriverSubtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'driver_subtypes',
            function (Blueprint $table) {
                $table->increments('driver_subtype_id')->index('index_driver_subtype_id');
                $table->integer('driver_type_id')->unsigned()->index('drivers_types_type_id');
                $table->string('name');
                $table->string('value');
                $table->timestamps(6);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers_subtypes');
    }
}
