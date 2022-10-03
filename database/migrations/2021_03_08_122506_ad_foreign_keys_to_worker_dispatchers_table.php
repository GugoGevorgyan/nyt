<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AdForeignKeysToWorkerDispatchersTable
 */
class AdForeignKeysToWorkerDispatchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // @todo enable production
        Schema::table('worker_dispatchers', static function (Blueprint $table) {
//            $table
//                ->foreign('system_worker_id', 'worker_dispatchers_foreign_system_worker_id')
//                ->references('system_worker_id')
//                ->on('system_workers')
//                ->onUpdate('NO ACTION')
//                ->onDelete('CASCADE');
//
//            $table
//                ->foreign('franchise_sub_phone_id', 'worker_dispatchers_foreign_franchise_sub_phone_id')
//                ->references('franchise_sub_phone_id')
//                ->on('franchise_sub_phones')
//                ->onUpdate('NO ACTION')
//                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('worker_dispatchers', function (Blueprint $table) {
            $table->dropForeign('worker_dispatchers_foreign_system_worker_id');
            $table->dropForeign('worker_dispatchers_foreign_franchise_sub_phone_id');
        });
    }
}
