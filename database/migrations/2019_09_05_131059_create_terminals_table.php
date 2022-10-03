<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateTerminalsTable
 */
class CreateTerminalsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'terminals',
            static function (Blueprint $table) {
                $table->increments('terminal_id')->index('terminal_index_id');
                $table->unsignedInteger('park_id')->nullable()->index('terminal_park_index_id');
                $table->unsignedInteger('auth_driver_id')->nullable()->index('terminal_park_index_auth_driver_id');
                $table->string('name', 150)->unique();
                $table->string('password', 250);

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
        Schema::drop('terminals');
    }

}
