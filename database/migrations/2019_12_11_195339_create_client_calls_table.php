<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_calls', function (Blueprint $table) {
            $table->increments('client_call_id')->index('index_client_call_id');
            $table->integer('franchise_id')->unsigned()->index('calls_franchises_id');
            $table->integer('franchise_phone_id')->unsigned()->index('calls_franchise_phones_id');
            $table->integer('franchise_sub_phone_id')->unsigned()->nullable()->index('calls_franchise_sub_phones_id');
            $table->integer('system_worker_id')->unsigned()->nullable()->index('calls_system_workers_id');
            $table->integer('client_id')->unsigned()->nullable()->index('calls_client_id');
            $table->integer('workerable_id')->nullable();
            $table->string('workerable_type', 120)->nullable();
            $table->string('client_phone');
            $table->timestamp('call_start', 6)->nullable();
            $table->timestamp('call_end', 6)->nullable();
            $table->integer('call_duration')->nullable();
            $table->unsignedSmallInteger('time_ago')->default(0);
            $table->boolean('incoming')->default(true);
            $table->boolean('answered')->default(false);
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_calls');
    }
}
