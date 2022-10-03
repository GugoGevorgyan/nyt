<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateCarOptionsTable
 */
class CreateCarOptionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('car_options', function (Blueprint $table) {
            $table->increments('car_option_id')->index('index_car_option_id');
            $table->string('option', 50)->nullable();
            $table->char('name', 50);
            $table->char('value', 50);
            $table->decimal('price', 6)->comment('DEFAULT PRICE');
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
        Schema::drop('order_options');
    }

}
