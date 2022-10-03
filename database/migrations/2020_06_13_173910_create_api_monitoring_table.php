<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateApiMonitoringTable
 */
class CreateApiMonitoringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_monitoring', function (Blueprint $table) {
            $table->increments('api_monitoring_id')->default(null);
            $table->unsignedInteger('api_key_id')->nullable();
            $table->string('api', 150)->default(null);
            $table->string('request', 500)->default('');
            $table->enum('request_method', ['GET', 'POST', 'PUT', 'DELETE'])->default('GET');
            $table->string('response_code')->default(null);
            $table->boolean('error')->default(null);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_monitoring');
    }
}
