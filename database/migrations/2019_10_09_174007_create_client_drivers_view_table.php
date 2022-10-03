<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateClientDriversViewTable
 */
class CreateClientDriversViewTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_drivers_view', static function (Blueprint $table) {
            $table->increments('client_driver_view_id')->index('client_drivers_view_client_driver_view_id');
            $table->unsignedInteger('clientable_id')->nullable()->index('client_driver_view_client_id');
            $table->string('clientable_type')->nullable()->index('client_driver_view_client_type');
            $table->json('driver')->nullable();
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
        Schema::drop('client_drivers_view');
    }

}
