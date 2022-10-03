<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCarsClassTable
 */
class CreateCarsClassTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cars_class', function (Blueprint $table) {
            $table->increments('car_class_id')->index('index_car_class_id');
            $table->string('class_name', 100);
            $table->string('description', 500)->nullable();
            $table->string('image', 300)->unique()->nullable();
            $table->string('name', 50)->unique()->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('cars_class');
    }

}
