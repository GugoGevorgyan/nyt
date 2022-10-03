<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateOrderMeetsTable
 */
class CreateOrderMeetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('order_meets', static function (Blueprint $table) {
            $table->increments('order_meet_id')->index('index_order_meet_id');
            $table->unsignedInteger('order_id')->index('order_meet_order_id');
            $table->unsignedInteger('place_id')->index('order_meet_place_id');
            $table->string('place_type');
            $table->string('info')->nullable();
            $table->text('text')->nullable();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_meets');
    }
}
