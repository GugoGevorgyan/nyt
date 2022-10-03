<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateMetrosTable
 */
class CreateMetrosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'metros',
            function (Blueprint $table) {
                $table->increments('metro_id')->index('index_metro_id');
                $table->unsignedInteger('city_id')->nullable()->index('airports_index_city_id');
                $table->string('name', 200);
                $table->string('input', 200)->nullable();
                $table->string('address', 300)->nullable();
                $table->decimal('lat', 10, 8)->nullable()->index('metros_index_latitude');
                $table->decimal('lut', 11, 8)->nullable()->index('metros_index_longitude');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::drop('metros');
    }

}
