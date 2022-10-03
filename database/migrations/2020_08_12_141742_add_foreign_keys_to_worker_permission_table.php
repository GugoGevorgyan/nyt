<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToWorkerPermissionTable
 */
class AddForeignKeysToWorkerPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'worker_permission',
            static function (Blueprint $table) {
                $table->foreign('system_worker_id', 'worker_permission_foreign_system_worker_id')
                    ->references('system_worker_id')
                    ->on('system_workers')
                    ->onUpdate('CASCADE')
                    ->onDelete('NO ACTION');

                $table->foreign('permission_id', 'worker_permission_foreign_permission_id')
                    ->references('permission_id')
                    ->on('permissions')
                    ->onUpdate('CASCADE')
                    ->onDelete('NO ACTION');
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
        Schema::table(
            'worker_permission',
            static function (Blueprint $table) {
                $table->dropForeign('worker_permission_foreign_system_worker_id');
                $table->dropForeign('worker_permission_foreign_permission_id');
            }
        );
    }
}
