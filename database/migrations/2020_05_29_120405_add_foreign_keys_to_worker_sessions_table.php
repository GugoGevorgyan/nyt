<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToWorkerSessionsTable
 */
class AddForeignKeysToWorkerSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'worker_sessions',
            static function (Blueprint $table) {
                $table->foreign('quit_worker_id', 'worker_sessions_foreign_quit_worker_id')
                    ->references('system_worker_id')
                    ->on('system_workers')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE');

                $table->foreign('logged_worker_id', 'worker_sessions_foreign_logged_worker_id')
                    ->references('system_worker_id')
                    ->on('system_workers')
                    ->onUpdate('NO ACTION')
                    ->onDelete('CASCADE');
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
            'worker_sessions',
            static function (Blueprint $table) {
                $table->dropForeign('worker_sessions_foreign_quit_worker_id');
                $table->dropForeign('worker_sessions_foreign_logged_worker_id');
            }
        );
    }
}
