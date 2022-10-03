<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateWorkerDispatchersTable
 */
class CreateWorkerDispatchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_dispatchers', function (Blueprint $table) {
            $table->increments('worker_dispatcher_id')->index('index_worker_dispatcher_id');
            $table->unsignedInteger('system_worker_id')->index('system_workers_id');
            $table->unsignedInteger('franchise_sub_phone_id')->index('franchise_sub_phones_id');
            $table->boolean('atc_logged')->default(false);
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
        Schema::dropIfExists('worker_dispatchers');
    }
}
