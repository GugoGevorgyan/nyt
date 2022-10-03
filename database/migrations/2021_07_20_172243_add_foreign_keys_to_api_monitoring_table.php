<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignKeysToApiMonitoringTable
 */
class AddForeignKeysToApiMonitoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('api_monitoring', function (Blueprint $table) {
            $table->foreign('api_key_id', 'api_monitoring_api_key_id')
                ->references('api_key_id')
                ->on('api_keys')
                ->onUpdate('NO ACTION')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('api_monitoring', function (Blueprint $table) {
            $table->dropForeign('api_monitoring_api_key_id');
        });
    }
}
