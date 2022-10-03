<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateWorkerPermissionTable
 */
class CreateWorkerPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'worker_permission',
            static function (Blueprint $table) {
                $table->increments('worker_permission_id')->index('worker_permission_id');
                $table->unsignedInteger('system_worker_id')->index('system_worker_permission_worker_id');
                $table->unsignedInteger('permission_id')->index('worker_permission_permission_id');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('worker_permission');
    }
}
