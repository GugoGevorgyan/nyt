<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderStagesCordTable
 */
class CreateOrderStagesCordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'order_stages_cord',
            static function (Blueprint $table) {
                $table->increments('order_stage_cord_id')->index('order_stages_cord_index_id');
                $table->unsignedInteger('order_id')->index('order_stages_cord_index_order_id');
                $table->json('accept');
                $table->dateTime('accepted')->nullable();
                $table->json('on_way');
                $table->dateTime('on_wayed')->nullable();
                $table->json('in_place');
                $table->dateTime('in_placed')->nullable();
                $table->json('start');
                $table->dateTime('started')->nullable();
                $table->json('pauses');
                $table->json('end');
                $table->dateTime('ended')->nullable();
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
        Schema::dropIfExists('order_stages_cord');
    }
}
