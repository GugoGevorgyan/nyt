<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateWorkerRoleTable
 */
class CreateWorkerRoleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'worker_role',
            function (Blueprint $table) {
                $table->increments('worker_role_id')->index('index_worker_role_id');
                $table->unsignedInteger('system_worker_id')->index('worker_role_worker_id');
                $table->unsignedInteger('role_id')->index('worker_role_id');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::drop('worker_role');
    }

}
