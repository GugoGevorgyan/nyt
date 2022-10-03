<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 *
 */
class CreateFirewallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('firewall', static function (Blueprint $table) {
            $table->increments('firewall_id')->index('index_firewall_id');
            $table->unsignedInteger('ip')->index('index_firewall_ip');
            $table->boolean('blocked')->default(false);
            $table->string('url', 300)->nullable();
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
        Schema::drop('firewalls');
    }

}
