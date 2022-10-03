<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderWorkerCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_worker_comments', function (Blueprint $table) {
            $table->increments('order_worker_comment_id');
            $table->unsignedInteger('order_id')->index('order_worker_comments_order_id');
            $table->unsignedInteger('system_worker_id')->index('order_worker_comments_system_worker_id');
            $table->boolean('driver')->default(0);
            $table->text('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_worker_comments');
    }
}
