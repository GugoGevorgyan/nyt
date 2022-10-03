<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDriverTypeOptionalsTable
 */
class CreateDriverTypeOptionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('driver_type_optionals', function (Blueprint $table) {
            $table->increments('driver_type_optional_id')->index('index_driver_type_optional_id');
            $table->string('name');
            $table->text('description');
            $table->boolean('valued')->default(false);
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
        Schema::dropIfExists('driver_type_optionals');
    }
}
