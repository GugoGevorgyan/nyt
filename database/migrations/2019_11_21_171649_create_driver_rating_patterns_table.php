<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDriverRatingPatternsTable
 */
class CreateDriverRatingPatternsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'driver_rating_patterns',
            static function (Blueprint $table) {
                $table->increments('driver_rating_pattern_id')->index('index_driver_rating_system_pattern_id');
                $table->tinyInteger('type')->unique();
                $table->char('name')->unique();
                $table->char('alias');
                $table->string('description', 500);
                $table->double('value', 3, 1);
                $table->enum('symbol', ['increment', 'decrement']);
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
        Schema::dropIfExists('driver_rating_system');
    }
}
