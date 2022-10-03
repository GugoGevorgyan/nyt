<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateDriverStatusesTable
 */
class CreateDriverStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('driver_statuses', static function (Blueprint $table) {
            $table->increments('driver_status_id')->index('index_driver_status_id');
            $table->char('name', 50);
            $table->tinyInteger('status');
            $table->string('text');
            $table->string('color');
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
        Schema::dropIfExists('driver_statuses');
    }
}
