<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateWorkerOperatorsTable
 */
class CreateWorkerOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'worker_operators',
            function (Blueprint $table) {
                $table->increments('worker_operator_id')->index('index_worker_operator_id');
                $table->unsignedInteger('system_worker_id')->index('system_workers_id');
                $table->unsignedInteger('franchise_sub_phone_id')->index('franchise_sub_phones_id');
                $table->boolean('atc_logged')->default(false);
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
        Schema::dropIfExists('worker_operators');
    }
}
