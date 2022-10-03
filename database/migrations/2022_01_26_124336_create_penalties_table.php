<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->increments('penalty_id');
            $table->unsignedInteger('debt_id');
            $table->integer('offense_id');
            $table->string('offense_date');
            $table->string('offense_time');
            $table->string('offense_location');
            $table->string('pay_bill_date');
            $table->string('last_bill_date');
            $table->boolean('status')->default(0);
            $table->decimal('lat', 10, 8);
            $table->decimal('lut', 11, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penalties');
    }
}
