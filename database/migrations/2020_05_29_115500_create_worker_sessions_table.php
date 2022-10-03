<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateWorkerSessionsTable
 */
class CreateWorkerSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'worker_sessions',
            static function (Blueprint $table) {
                $table->increments('worker_session_id')->index('worker_sessions_index_id');

                $table->unsignedInteger('quit_worker_id')->index('worker_sessions_index_quit_worker_id');
                $table->unsignedInteger('logged_worker_id')->index('worker_sessions_index_logged_worker_id')->nullable();

                $table->string('token', 128)->unique();
                $table->dateTime('quit_time');
                $table->dateTime('logged_time')->nullable();

                $table->softDeletes();
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
        Schema::dropIfExists('worker_sessions');
    }
}
